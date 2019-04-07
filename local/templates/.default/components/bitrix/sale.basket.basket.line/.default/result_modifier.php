<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arResult['NUM_PRODUCTS'] = 0;
foreach ($arResult["CATEGORIES"]["READY"] as $basketItem) {
    $arResult['NUM_PRODUCTS'] += $basketItem["QUANTITY"];
}
