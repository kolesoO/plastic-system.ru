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
if (is_array($arParams["IMAGE_SIZE"])) {
    //кеширование изображений
    foreach ($arResult["STORES"] as &$arItem) {
        if (is_array($arItem["DETAIL_IMG"])) {
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
    }
    unset($arItem);
    //end
}

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["STORES_COUNT"]);
}