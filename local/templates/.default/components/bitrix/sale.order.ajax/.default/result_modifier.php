<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$arResult["PERSON_TYPE_COUNT"] = count($arResult["PERSON_TYPE"]);
$arResult["DELIVERY_COUNT"] = count($arResult["DELIVERY"]);
$arResult["PAY_SYSTEM_COUNT"] = count($arResult["PAY_SYSTEM"]);

foreach ($arResult["PERSON_TYPE"] as $arPersonType) {
    if ($arPersonType["CHECKED"] == "Y") {
        $arResult["ACTIVE_PERSON_TYPE"] = $arPersonType["ID"];
    }
}