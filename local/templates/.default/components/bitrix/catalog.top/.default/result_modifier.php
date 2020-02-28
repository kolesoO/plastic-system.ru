<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$hasResizeImage = is_array($arParams["IMAGE_SIZE"]);
$arResult["ITEMS_COUNT"] = count($arResult["ITEMS"]);
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

foreach ($arResult["ITEMS"] as &$arItem) {
    $arItem["OFFER_ID_SELECTED"] = 0; // непонятная логика формирования этого ключа, так как для некоторых товаров пишется не порядкой номер ТП, а ID ТП
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
        $arOfferKeys = [];
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
                $arOfferKeys[$arOffer["ID"]] = $key;
            }
        }
        if (count($arOfferKeys) > 0 && isset($arResult["CATALOGS"][$arParams["IBLOCK_ID"]]) && count($arParams["OFFERS_PROPERTY_CODE"]) > 0) {
            //Доп. свойства торг предложений
            $rsElems = \CIBlockElement::GetList(
                [],
                ["IBLOCK_ID" => $arResult["CATALOGS"][$arParams["IBLOCK_ID"]]["IBLOCK_ID"], "ID" => array_keys($arOfferKeys)],
                false,
                false,
                ["ID", "IBLOCK_ID"]
            );
            while ($rsIblockItem = $rsElems->GetNextElement()) {
                $arFields = $rsIblockItem->getFields();
                $arProps = $rsIblockItem->getProperties();
                foreach($arProps as $code => $value) {
                    if (in_array($code, $arParams["OFFERS_PROPERTY_CODE"]) && isset($arItem["OFFERS"][$arOfferKeys[$arFields["ID"]]])) {
                        $arItem["OFFERS"][$arOfferKeys[$arFields["ID"]]]["PROPERTIES"][$code] = $value;
                    }
                }
            }
            //end
            //обновление остатков на складе
            $rsStoreProduct = \CCatalogStore::GetList(
                [],
                ["ID" => STORE_ID, "PRODUCT_ID" => array_keys($arOfferKeys)],
                false,
                false,
                ["ID", "PRODUCT_AMOUNT"]
            );
            while ($arStoreProduct = $rsStoreProduct->fetch()) {
                $arItem["OFFERS"][$arOfferKeys[$arFields["ID"]]]["CATALOG_QUANTITY"] = $arStoreProduct["PRODUCT_AMOUNT"];
                if (intval($arStoreProduct["PRODUCT_AMOUNT"]) == 0) {
                    $arItem["OFFERS"][$arOfferKeys[$arFields["ID"]]]["CAN_BUY"] = false;
                }
            }
            //end
        }
    }
}
unset($arItem);

//шаблон для элемента каталога
$arResult["INNER_TEMPLATE"] = ".default";
if ($arParams["DEVICE_TYPE"] == "DESKTOP") {
    $arResult["SET_AREA"] = true;
} elseif ($arParams["DEVICE_TYPE"] == "MOBILE") {
    $arResult["INNER_TEMPLATE"] = ".default-mobile";
}
//end

//идентификация слайдера
$arResult["IS_SLIDER"] = $arResult["ITEMS_COUNT"] > $arParams["LINE_ELEMENT_COUNT"];
//end

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["ITEMS_COUNT", "SET_AREA", "INNER_TEMPLATE", "IS_SLIDER"]);
}