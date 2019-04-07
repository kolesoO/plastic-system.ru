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

$arResult["OFFERS_COUNT"] = count($arResult["ITEM"]["OFFERS"]);

if ($arResult["OFFERS_COUNT"] > 0) {
    foreach ($arResult["ITEM"]["OFFERS"] as &$arOffer) {
        if (!is_array($arOffer["PREVIEW_PICTURE"]) && is_array($arResult["ITEM"]["PREVIEW_PICTURE"])) {
            $arOffer["PREVIEW_PICTURE"] = $arResult["ITEM"]["PREVIEW_PICTURE"];
        }
    }
    unset($arOffer);
}

$cp = $this->__component;
if (is_object($cp)) {
    $cp->SetResultCacheKeys(["OFFERS_COUNT"]);
}