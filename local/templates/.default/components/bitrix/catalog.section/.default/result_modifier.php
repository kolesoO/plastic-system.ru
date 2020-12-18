<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
//$arParams = $component->applyTemplateModifications();
$hasResizeImage = is_array($arParams["IMAGE_SIZE"]);
$arResult["ITEMS_COUNT"] = count($arResult["ITEMS"]);
$arItemsRelations = [];
$arOfferKeys = [];
$arOfferKeysForAmount = [];
$arOfferId = [];

foreach ($arResult["ITEMS"] as $arItem) {
    foreach ($arItem["OFFERS"] as $arOffer) {
        $arOfferId[] = $arOffer["ID"];
    }
}

//чистые торг предложения (по глобавльному фильру OFFERS)
if (is_array($GLOBALS[$arParams["FILTER_NAME"]]["OFFERS"]) && count($arOfferId) > 0) {
    $arPureOffersId = [];
    $rsElems = \CIBlockElement::GetList(
        [],
        array_merge(
            [
                "IBLOCK_ID" => $arResult["CATALOGS"][$arParams["IBLOCK_ID"]]["IBLOCK_ID"],
                "ID" => $arOfferId
            ],
            $GLOBALS[$arParams["FILTER_NAME"]]["OFFERS"]
        ),
        false,
        false,
        ["ID", "IBLOCK_ID"]
    );
    while ($arOffer = $rsElems->GetNext()) {
        $arPureOffersId[] = $arOffer["ID"];
    }
}
//end

foreach ($arResult["ITEMS"] as $itemKey => &$arItem) {
    $arItem["OFFER_ID_SELECTED"] = 0; // непонятная логика формирования этого ключа, так как для некоторых товаров пишется не порядкой номер ТП, а ID ТП
    $arItemsRelations[$arItem["ID"]] = $itemKey;
    //ресайз и кеширование изображений
    if (is_array($arItem["PREVIEW_PICTURE"]) && $hasResizeImage) {
        $thumb = \CFile::ResizeImageGet(
            $arItem["PREVIEW_PICTURE"],
            ["width" => $arParams["IMAGE_SIZE"]["WIDTH"], "height" => $arParams["IMAGE_SIZE"]["HEIGHT"]],
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
        $arItem["PREVIEW_PICTURE"]["SRC"] = ($thumb["src"] ? $thumb["src"] : $arItem["PREVIEW_PICTURE"]["SRC"]);
    }
    //end
    if (is_array($arItem["OFFERS"]) && count($arItem["OFFERS"]) > 0) {
        foreach ($arItem["OFFERS"] as $key => $arOffer) {
            if (isset($arPureOffersId) && !in_array($arOffer["ID"], $arPureOffersId)) {
                unset($arItem["OFFERS"][$key]);
                if ($arItem["OFFER_ID_SELECTED"] == $key) {
                    unset($arItem["OFFER_ID_SELECTED"]);
                }
                continue;
            }
            if (!isset($arItem["OFFER_ID_SELECTED"])) {
                $arItem["OFFER_ID_SELECTED"] = $key;
            }
            //ресайз и кеширование изображений
            if (is_array($arOffer["PREVIEW_PICTURE"]) && $hasResizeImage) {
                $thumb = \CFile::ResizeImageGet(
                    $arOffer["PREVIEW_PICTURE"],
                    ["width" => $arParams["IMAGE_SIZE"]["WIDTH"], "height" => $arParams["IMAGE_SIZE"]["HEIGHT"]],
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
                $arOffer["PREVIEW_PICTURE"]["SRC"] = ($thumb["src"] ? $thumb["src"] : $arOffer["PREVIEW_PICTURE"]["SRC"]);
            }
            //end
            if (!is_array($arOffer["PROPERTIES"]) || count($arOffer["PROPERTIES"]) == 0) {
                $arOfferKeys[$arOffer["ID"]] = [
                    "KEY" => $key,
                    "ITEM_KEY" => $itemKey
                ];
            }
            $arOfferKeysForAmount[$arOffer["ID"]] = [
                "KEY" => $key,
                "ITEM_KEY" => $itemKey
            ];
        }
    }
}
unset($arItem);

if (count($arItemsRelations) > 0 && count($arParams["PROPERTY_CODE"]) > 0) {
    //Доп. свойства основных товаров
    $addAllProps = in_array("*", $arParams["PROPERTY_CODE"]);
    $rsElems = \CIBlockElement::GetList(
        [],
        ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => array_keys($arItemsRelations)],
        false,
        false,
        ["ID", "IBLOCK_ID"]
    );
    while ($rsIblockItem = $rsElems->GetNextElement()) {
        $arFields = $rsIblockItem->getFields();
        $arProps = $rsIblockItem->getProperties();
        foreach($arProps as $code => $value) {
            if (in_array($code, $arParams["PROPERTY_CODE"]) || $addAllProps) {
                $arResult["ITEMS"][$arItemsRelations[$arFields["ID"]]]["PROPERTIES"][$code] = $value;
            }
        }
    }
    //end
}

if (count($arOfferKeys) > 0 && isset($arResult["CATALOGS"][$arParams["IBLOCK_ID"]]) && count($arParams["OFFERS_PROPERTY_CODE"]) > 0) {
    //Доп. свойства торг предложений
    $rsElems = \CIBlockElement::GetList(
        [],
        [
            "IBLOCK_ID" => $arResult["CATALOGS"][$arParams["IBLOCK_ID"]]["IBLOCK_ID"],
            "ID" => array_keys($arOfferKeys)
        ],
        false,
        false,
        ["ID", "IBLOCK_ID"]
    );
    while ($rsIblockItem = $rsElems->GetNextElement()) {
        $arFields = $rsIblockItem->getFields();
        $arProps = $rsIblockItem->getProperties();
        $itemKey = $arOfferKeys[$arFields["ID"]]["ITEM_KEY"];
        $offerKey = $arOfferKeys[$arFields["ID"]]["KEY"];
        foreach ($arProps as $code => $value) {
            if (in_array($code, $arParams["OFFERS_PROPERTY_CODE"]) && isset($arResult["ITEMS"][$itemKey]["OFFERS"][$offerKey])) {
                $arResult["ITEMS"][$itemKey]["OFFERS"][$offerKey]["PROPERTIES"][$code] = $value;
            }
        }
    }
    //end
}

if (count($arOfferKeysForAmount) > 0) {
    //обновление остатков на складе
    $rsStoreProduct = \CCatalogStore::GetList(
        [],
        ["ID" => STORE_ID, "PRODUCT_ID" => array_keys($arOfferKeysForAmount)],
        false,
        false,
        ["*"]
    );
    while ($arStoreProduct = $rsStoreProduct->fetch()) {
        $itemKey = $arOfferKeysForAmount[$arStoreProduct["ELEMENT_ID"]]["ITEM_KEY"];
        $offerKey = $arOfferKeysForAmount[$arStoreProduct["ELEMENT_ID"]]["KEY"];
        $arResult["ITEMS"][$itemKey]["OFFERS"][$offerKey]["CATALOG_QUANTITY"] = $arStoreProduct["PRODUCT_AMOUNT"];
        if (intval($arStoreProduct["PRODUCT_AMOUNT"]) == 0) {
            $arResult["ITEMS"][$itemKey]["OFFERS"][$offerKey]["CAN_BUY"] = false;
        }
    }
    //end
}

$arResult["INNER_TEMPLATE"] = ($arParams["DEVICE_TYPE"] == "MOBILE" ? ".default-mobile" : ".default");

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["ITEMS_COUNT"]);
}

if (\Bitrix\Main\Loader::includeModule("impulsit.expansionsite"))
{
    #SCHEMA ORG + OPEN GRAPH
    $cp = $this->__component;
    if (is_object($cp))
    {
        foreach($arResult["ITEMS"] as $arItem)
        {
            $elementsId[] = $arItem["ID"];
        }

        $arResult["NAV_RESULT_EPILOG"] = array(
            "ELEMENTS_ID" => $elementsId,
            "bNavStart" => $arResult["NAV_RESULT"]->bNavStart,
            "NavNum" => $arResult["NAV_RESULT"]->NavNum,
            "NavPageCount" => $arResult["NAV_RESULT"]->NavPageCount,
            "NavPageNomer" => $arResult["NAV_RESULT"]->NavPageNomer,
            "NavPageSize" => $arResult["NAV_RESULT"]->NavPageSize,
            "NavRecordCount" => $arResult["NAV_RESULT"]->NavRecordCount,
            "bFirstPrintNav" => $arResult["NAV_RESULT"]->bFirstPrintNav,
            "PAGEN" => $arResult["NAV_RESULT"]->PAGEN,
            "SIZEN" => $arResult["NAV_RESULT"]->SIZEN,
            "bFromLimited" => $arResult["NAV_RESULT"]->bFromLimited,
            "sSessInitAdd" => $arResult["NAV_RESULT"]->sSessInitAdd,
            "nPageWindow" => $arResult["NAV_RESULT"]->nPageWindow,
            "nSelectedCount" => $arResult["NAV_RESULT"]->nSelectedCount
        );
    
       $cp->SetResultCacheKeys(array('NAV_RESULT_EPILOG', 'SECTION_PAGE_URL'));
    }
}
