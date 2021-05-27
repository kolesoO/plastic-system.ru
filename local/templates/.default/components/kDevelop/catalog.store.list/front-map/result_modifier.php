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

global $USER_FIELD_MANAGER;

$arResult["STORES_COUNT"] = count($arResult["STORES"]);

foreach ($arResult["STORES"] as $key => $arItem) {
    $arFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE", $arItem["ID"]);

    if (!isset($arFields["UF_CUSTOM_COORDS"]) || !$arFields["UF_CUSTOM_COORDS"]['VALUE']) {
        unset($arResult["STORES"][$key]);

        continue;
    }

    $arResult["STORES"][$key]["UF_CUSTOM_COORDS"] = explode(",", $arFields["UF_CUSTOM_COORDS"]["VALUE"]);
    $arResult["STORES"][$key]["UF_CITY_NAME"] = $arFields["UF_CITY_NAME"]["VALUE"];
}

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["STORES_COUNT"]);
}
