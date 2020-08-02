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
$hasResizeImg = is_array($arParams["IMAGE_SIZE"]);

foreach ($arResult["STORES"] as &$arItem) {
    //пользовательские свойства
    $arFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE", $arItem["ID"]);
    if (intval($arFields["UF_SCHEME"]["VALUE"]) > 0) {
        $arItem["UF_SCHEME"] = \CFile::GetFileArray($arFields["UF_SCHEME"]["VALUE"]);
        $arItem["UF_SCHEME"]["EXTENSION"] = pathinfo($arItem["UF_SCHEME"]["ORIGINAL_NAME"], PATHINFO_EXTENSION);
    }
    if (isset($arFields["UF_CUSTOM_COORDS"])) {
        $arItem["UF_CUSTOM_COORDS"] = explode(",", $arFields["UF_CUSTOM_COORDS"]["VALUE"]);
        $arItem["UF_CITY_NAME"] = $arFields["UF_CITY_NAME"]["VALUE"];
    }
    if (isset($arFields["UF_FROM_N"]) && strlen($arFields["UF_FROM_N"]["VALUE"]) > 0) {
        $arItem["UF_FROM_N"] = $arFields["UF_FROM_N"]["VALUE"];
    }
    if (isset($arFields["UF_FROM_S"]) && strlen($arFields["UF_FROM_S"]["VALUE"]) > 0) {
        $arItem["UF_FROM_S"] = $arFields["UF_FROM_S"]["VALUE"];
    }
    if (strlen($arItem["UF_FROM_N"]) == 0 || strlen($arItem["UF_FROM_S"]) == 0) {
        unset($arItem["UF_FROM_N"]);
        unset($arItem["UF_FROM_S"]);
    }
    if (strlen($arFields['UF_MAP_LINK']['VALUE']) > 0) {
        $arItem['UF_MAP_LINK'] = $arFields['UF_MAP_LINK']['VALUE'];
    }
    //end

    $arItem['MAP_BALLOON_CONTENT'] = isset($arFields['UF_MAP_TITLE']) && strlen($arFields['UF_MAP_TITLE']['VALUE']) > 0
        ? $arFields['UF_MAP_TITLE']['VALUE']
        : $arItem['ADDRESS'];

    //кеширование изображений
    if (is_array($arItem["DETAIL_IMG"]) && $hasResizeImg) {
        $thumb = \CFile::ResizeImageGet(
            $arItem["DETAIL_IMG"],
            ["width" => $arParams["IMAGE_SIZE"]["WIDTH"], "height" => $arParams["IMAGE_SIZE"]["HEIGHT"]],
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
        if ($thumb["src"]) {
            $arItem["DETAIL_IMG"]["SRC"] = $thumb["src"];
        }
    }
    //end
}
unset($arItem);

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["STORES_COUNT"]);
}
