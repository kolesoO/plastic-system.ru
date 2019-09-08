<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @global array $arParams */

if (isset($arResult["SHOW_OFFER_FIELDS"]["NAME"]) && strlen($arResult["SHOW_OFFER_FIELDS"]["NAME"]) > 0) {
    $arResult["SHOW_FIELDS"]["NAME"] = $arResult["SHOW_OFFER_FIELDS"]["NAME"];
}
if (isset($arResult["SHOW_OFFER_FIELDS"]["PREVIEW_PICTURE"]) && is_array($arResult["SHOW_OFFER_FIELDS"]["PREVIEW_PICTURE"])) {
    $arResult["SHOW_FIELDS"]["PREVIEW_PICTURE"] = $arResult["SHOW_OFFER_FIELDS"]["PREVIEW_PICTURE"];
}

foreach ($arResult["ITEMS"] as &$arItem) {
    $arItem["DETAIL_PAGE_URL"] = $arItem["DETAIL_PAGE_URL"].\kDevelop\Help\Tools::getOfferPrefixInUrl().$arItem["OFFER_FIELDS"]["CODE"]."/";
    unset($arItem["OFFER_FIELDS"]["CODE"]);
}
unset($arItem);