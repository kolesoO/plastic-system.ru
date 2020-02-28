<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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

$curPage = $APPLICATION->GetCurPage(false);
if (strpos($curPage, "filter") !== false && strpos($curPage, "clear") === false) {
    $arResult["IS_APPLIED"] = true;
}

foreach($arResult["ITEMS"] as $PID => $arItem) {
    if ($arItem["DISPLAY_TYPE"] == "F") {
        uasort($arResult["ITEMS"][$PID]["VALUES"], function($item1, $item2) {
            return $item1["VALUE"] > $item2["VALUE"];
        });
    }
}