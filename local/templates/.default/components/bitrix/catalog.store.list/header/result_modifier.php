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

$arResult["STORES_COUNT"] = count($arResult["STORES"]);

foreach ($arResult["STORES"] as $key => $arItem) {
    if ($arItem["ID"] == $arParams["CUR_ID"]) {
        $arResult["CUR_STORE"] = $arItem;
        unset($arResult["STORES"][$key]);
        break;
    }
}

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["STORES_COUNT", "CUR_STORE", "STORES"]);
}