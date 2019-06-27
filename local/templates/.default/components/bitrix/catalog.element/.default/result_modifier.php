<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$hasResizeImage = is_array($arParams["IMAGE_SIZE"]);
$updateOfferProps = isset($arResult["CATALOGS"][$arParams["IBLOCK_ID"]]) && count($arParams["OFFERS_PROPERTY_CODE"]) > 0;
$arOfferKeys = [];
$arResult["OFFERS_COUNT"] = count($arResult["OFFERS"]);

//на входе имеем код выбранного sku, ищем ключ этого sku
if (strlen($arParams["OFFER_CODE_SELECTED"]) > 0) {
    foreach ($arResult["OFFERS"] as $key => $arOffer) {
        if ($arOffer["CODE"] == $arParams["OFFER_CODE_SELECTED"]) {
            $arResult["OFFER_ID_SELECTED"] = $key;
            break;
        }
    }
}

if (isset($arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]])) {
    foreach ($arResult["OFFERS"] as $key => &$arOffer) {
        if ($updateOfferProps && (!is_array($arOffer["PROPERTIES"]) || count($arOffer["PROPERTIES"]) == 0)) {
            $arOfferKeys[$arOffer["ID"]] = $key;
        }
        $arOffer["DETAIL_PAGE_URL"] = str_replace(\kDevelop\Help\Tools::getOfferSefUrlTmp(), \kDevelop\Help\Tools::getOfferPrefixInUrl().$arOffer["CODE"], $arResult["DETAIL_PAGE_URL"]);
    }
    unset($arOffer);
    //Доп. свойства основного товара
    if (count($arResult["PROPERTIES"]) == 0 && $arParams["PROPERTY_CODE"] > 0) {
        if ($rsIblockItem = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $arResult["CATALOGS"][$arParams["IBLOCK_ID"]]["IBLOCK_ID"], "ID" => array_keys($arOfferKeys)],
            false,
            false,
            ["ID", "IBLOCK_ID"]
        )->GetNextElement()) {
            $arProps = $rsIblockItem->getProperties();
            foreach($arProps as $code => $value) {
                if (in_array($code, $arParams["PROPERTY_CODE"])) {
                    $arResult["PROPERTIES"][$code] = $value;
                }
            }
        }
    }
    //end
    //Доп. свойства торг предложений
    if (count($arOfferKeys) > 0) {
        $addAllProps = in_array("*", $arParams["OFFERS_PROPERTY_CODE"]);
        $rsElem = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $arResult["CATALOGS"][$arParams["IBLOCK_ID"]]["IBLOCK_ID"], "ID" => array_keys($arOfferKeys)],
            false,
            false,
            ["ID", "IBLOCK_ID"]
        );
        while ($rsIblockItem = $rsElem->GetNextElement()) {
            $arFields = $rsIblockItem->getFields();
            $arProps = $rsIblockItem->getProperties();
            foreach($arProps as $code => $value) {
                if (
                        isset($arResult["OFFERS"][$arOfferKeys[$arFields["ID"]]]) &&
                        (in_array($code, $arParams["OFFERS_PROPERTY_CODE"]) || $addAllProps)
                ) {
                    $arResult["OFFERS"][$arOfferKeys[$arFields["ID"]]]["PROPERTIES"][$code] = $value;
                }
            }
        }
    }
    //end
    //ресайз и кеширование изображений
    if ($hasResizeImage) {
        //детальное изображение
        if (!is_array($arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["DETAIL_PICTURE"])) {
            $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["DETAIL_PICTURE"] = $arResult["DETAIL_PICTURE"];
        }
        if (is_array($arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["DETAIL_PICTURE"])) {
            $thumb = \CFile::ResizeImageGet(
                $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["DETAIL_PICTURE"],
                ["width" => $arParams["IMAGE_SIZE"]["WIDTH"], "height" => $arParams["IMAGE_SIZE"]["HEIGHT"]],
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            if ($thumb["src"]) {
                $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["DETAIL_PICTURE"]["SRC"] = $thumb["src"];
            }
        }
        //end
        //доп. картинки
        if (is_array($arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"])) {
            $arPhoto = array();
            foreach ($arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $fileId) {
                $thumb = \CFile::ResizeImageGet(
                    \CFile::GetFileArray($fileId),
                    ["width" => $arParams["IMAGE_SIZE"]["WIDTH"], "height" => $arParams["IMAGE_SIZE"]["HEIGHT"]],
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
                if ($thumb["src"]) {
                    $arPhoto[] = $thumb["src"];
                }
            }
            $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["PROPERTIES"]["MORE_PHOTO"]["VALUE"] = $arPhoto;
        }
        //end
    }
    //end

    //разбивка торг предложений на 'с цветом' и 'с размером'
    $arResult["OFFERS_WITH_COLOR"] = [];
    $arResult["OFFERS_WITH_SIZE"] = [];
    $arResult["OFFERS_WITH_COLOR_COUNT"] = 0;
    $arResult["OFFERS_WITH_SIZE_COUNT"] = 0;
    foreach ($arResult["OFFERS"] as $key => &$arOffer) {
        if (strlen($arOffer["PROPERTIES"]["TSVET"]["VALUE"]) > 0) {
            $arResult["OFFERS_WITH_COLOR"][] = $arOffer["ID"];
            $arResult["OFFERS_WITH_COLOR_COUNT"] ++;
        }
        if (strlen($arOffer["PROPERTIES"]["RAZMER"]["VALUE"]) > 0 && $arOffer["ID"] != $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["ID"]){
            $arResult["OFFERS_WITH_SIZE"][] = $arOffer["ID"];
            $arResult["OFFERS_WITH_SIZE_COUNT"] ++;
        }
    }
    //end
}

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["OFFERS_COUNT", "OFFERS", "OFFER_ID_SELECTED", "PROPERTIES", "OFFERS_WITH_COLOR", "OFFERS_WITH_SIZE", "OFFERS_WITH_COLOR_COUNT", "OFFERS_WITH_SIZE_COUNT"]);
}