<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

global $USER_FIELD_MANAGER;

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$arResult["PERSON_TYPE_COUNT"] = count($arResult["PERSON_TYPE"]);
$arResult["DELIVERY_COUNT"] = count($arResult["DELIVERY"]);
$arResult["PAY_SYSTEM_COUNT"] = count($arResult["PAY_SYSTEM"]);

//тип платеьлщика
$counter = 0;
foreach ($arResult["PERSON_TYPE"] as $arPersonType) {
    if ($arPersonType["CHECKED"] == "Y") {
        $arResult["ACTIVE_PERSON_TYPE"] = $arPersonType["ID"];
        $arResult["ACTIVE_PERSON_TYPE_KEY"] = $counter;
    }
    $counter ++;
}
//end

//свойства заказа
foreach ($arResult["ORDER_PROP"]["USER_PROPS_N"] as &$arProp) {
    if ($arProp["IS_LOCATION"] == "Y" && defined("STORE_ID")) {
        if ($arStore = \CCatalogStore::GetList(
            ["SORT" => "ASC"],
            ["ACTIVE"=>"Y", "ID" => STORE_ID],
            false,
            false,
            ["ID", "ADDRESS"]
        )->fetch()) {
            $arFields = $USER_FIELD_MANAGER->GetUserFields("CAT_STORE", $arStore["ID"]);
            if (isset($arFields["UF_CITY_NAME"]) && strlen($arFields["UF_CITY_NAME"]["VALUE"]) > 0) {
                if ($arLocation = \CSaleLocation::GetList(
                    [],
                    ["CITY_NAME" => $arFields["UF_CITY_NAME"]["VALUE"]],
                    false,
                    false,
                    []
                )->fetch()) {
                    $arProp["VALUE"] = $arLocation["CITY_ID"];
                }
            }
        }
    }
}
//unset($arProp);
//end