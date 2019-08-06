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
$arResult["SECTIONS_COUNT"] = 0;

//разделы
$rsSection = \CIBlockSection::GetList(
    [],
    [
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "UF_FOR_CALC" => "1"
    ]
);
while ($arSection = $rsSection->fetch()) {
    $arResult["SECTIONS"][] = $arSection;
    $arResult["SECTIONS_COUNT"] ++;
}
//end

//свойства для фильтрации
/*$arResult["FILTER_PROPS"] = [];
$rsPropValue = \CIBlockPropertyEnum::GetList(
    [],
    ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "CODE" => ["SHIRINA_MM", "VYSOTA_MM", "OBEM_L"]]
);
while ($arValueInfo = $rsPropValue->fetch()) {
    $arResult["FILTER_PROPS"][$arValueInfo["PROPERTY_CODE"]][] = $arValueInfo;
}*/
//end

/*foreach ($arResult["ITEMS"] as &$arItem) {
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
        //Доп. свойства торг предложений
        if (count($arOfferKeys) > 0 && isset($arResult["CATALOGS"][$arParams["IBLOCK_ID"]]) && count($arParams["OFFERS_PROPERTY_CODE"]) > 0) {
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
        }
        //end
    }
}
unset($arItem);*/

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["ITEMS_COUNT", "SECTIONS", "SECTIONS_COUNT"]);
}