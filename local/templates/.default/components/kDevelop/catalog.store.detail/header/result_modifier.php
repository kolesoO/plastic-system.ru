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

//пользовательские свойства
$arFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE", $arResult["ID"]);
if (isset($arFields["UF_SCHEDULE"])) {
    $arResult["UF_SCHEDULE"] = $arFields["UF_SCHEDULE"]["VALUE"];
}
//end

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["UF_SCHEDULE"]);
}