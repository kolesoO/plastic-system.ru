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

    //разбивка торг предложений на 'с цветом', 'с размером', 'дно', 'ibox', 'Тип контейнера'
    $arResult["TSVET"] = $arResult["OFFERS_WITH_SIZE"] = $arResult["OFFERS_WITH_IBOX"] = $arResult["OFFERS_WITH_DNO"] = [
        "ID" => [],
        "COUNT" => 0,
        "TITLE" => ""
    ];
    $arValueCache = [];
    foreach ($arResult["OFFERS"] as $key => &$arOffer) {
        if (strlen($arOffer["PROPERTIES"]["TSVET"]["VALUE"]) > 0 && !in_array($arOffer["PROPERTIES"]["TSVET"]["VALUE"], $arValueCache)) {
            $arResult["TSVET"]["ID"][] = $arOffer["ID"];
            $arResult["TSVET"]["COUNT"] ++;
            $arValueCache[] = $arOffer["PROPERTIES"]["TSVET"]["VALUE"];
            if (strlen($arResult["TSVET"]["TITLE"]) == 0) {
                $arResult["TSVET"]["TITLE"] = $arOffer["PROPERTIES"]["TSVET"]["NAME"];
            }
        }
        if (strlen($arOffer["PROPERTIES"]["RAZMER"]["VALUE"]) > 0) {
            $arResult["RAZMER"]["ID"][] = $arOffer["ID"];
            $arResult["RAZMER"]["COUNT"] ++;
            $arValueCache[] = $arOffer["PROPERTIES"]["RAZMER"]["VALUE"];
            if (strlen($arResult["RAZMER"]["TITLE"]) == 0) {
                $arResult["RAZMER"]["TITLE"] = $arOffer["PROPERTIES"]["RAZMER"]["NAME"];
            }
        }
        if (strlen($arOffer["PROPERTIES"]["OPTSII_IBOX"]["VALUE"]) > 0) {
            $arResult["OPTSII_IBOX"]["ID"][] = $arOffer["ID"];
            $arResult["OPTSII_IBOX"]["COUNT"] ++;
            $arValueCache[] = $arOffer["PROPERTIES"]["OPTSII_IBOX"]["VALUE"];
            if (strlen($arResult["OPTSII_IBOX"]["TITLE"]) == 0) {
                $arResult["OPTSII_IBOX"]["TITLE"] = $arOffer["PROPERTIES"]["OPTSII_IBOX"]["NAME"];
            }
        }
        if (strlen($arOffer["PROPERTIES"]["DNO"]["VALUE"]) > 0) {
            $arResult["DNO"]["ID"][] = $arOffer["ID"];
            $arResult["DNO"]["COUNT"] ++;
            $arValueCache[] = $arOffer["PROPERTIES"]["DNO"]["VALUE"];
            if (strlen($arResult["DNO"]["TITLE"]) == 0) {
                $arResult["DNO"]["TITLE"] = $arOffer["PROPERTIES"]["DNO"]["NAME"];
            }
        }
        if (strlen($arOffer["PROPERTIES"]["TIP_KONTEYNERA"]["VALUE"]) > 0) {
            $arResult["TIP_KONTEYNERA"]["ID"][] = $arOffer["ID"];
            $arResult["TIP_KONTEYNERA"]["COUNT"] ++;
            $arValueCache[] = $arOffer["PROPERTIES"]["TIP_KONTEYNERA"]["VALUE"];
            if (strlen($arResult["TIP_KONTEYNERA"]["TITLE"]) == 0) {
                $arResult["TIP_KONTEYNERA"]["TITLE"] = $arOffer["PROPERTIES"]["TIP_KONTEYNERA"]["NAME"];
            }
        }
    }
    //end
}

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["OFFERS_COUNT", "OFFERS", "OFFER_ID_SELECTED", "PROPERTIES", "OFFERS_WITH_COLOR", "OFFERS_WITH_SIZE", "OFFERS_WITH_IBOX", "OFFERS_WITH_DNO"]);
}