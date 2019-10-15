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
        "UF_SHOW_IN_CALC" => "1"
    ]
);
while ($arSection = $rsSection->fetch()) {
    $arResult["SECTIONS"][] = $arSection;
    $arResult["SECTIONS_COUNT"] ++;
}
//end

//доступный список цветов
$arResult["COLORS"] = [];
$rsProp = \CIBlockPropertyEnum::GetList(
    [],
    ["IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU, "CODE" => "TSVET"]
);
while ($propItem = $rsProp->fetch()) {
    $arResult["COLORS"][$propItem["VALUE"]] = \kDevelop\Help\Tools::getOfferColor($propItem["VALUE"]);
}
//end

//основные настройки калькулятора
$rs = \CIBlockElement::GetList(
    [],
    ["IBLOCK_ID" => IBLOCK_SERVICE_CATALOG_SETTINGS, "CODE" => "general", "ACTIVE" => "Y"],
    false,
    false,
    ["ID", "IBLOCK_ID"]
);
if ($rsItem = $rs->getNextElement()) {
    $props = $rsItem->getProperties();
    foreach (["CALC_ITEM_HEIGHT", "CALC_WIDTH", "CALC_HEIGHT", "CALC_LENGTH"] as $code) {
        $arResult["CALCULATOR_SETTINGS"][$code] = $props[$code];
    }
}
//end

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["ITEMS_COUNT", "SECTIONS", "SECTIONS_COUNT", "COLORS"]);
}