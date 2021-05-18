<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", "Сравнение - Складские лотки и пластиковая тара от компании ООО «Пластик Система»");
$APPLICATION->SetTitle("Сравнение");
$APPLICATION->SetPageProperty("header_section-class", "section");

//подготовка парметров компонента
if (DEVICE_TYPE == "DESKTOP") {
    $elemsInRow = 5;
} elseif (DEVICE_TYPE == "TABLET") {
    $elemsInRow = 3;
} else {
    $elemsInRow = 1;
}
//end

$APPLICATION->IncludeComponent (
    "bitrix:catalog.compare.result",
    "",
    [
        "AJAX_MODE" => "Y",
        "NAME" => "CATALOG_COMPARE_LIST",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
        "FIELD_CODE" => array("NAME", "PREVIEW_PICTURE"),
        "PROPERTY_CODE" => array(
            "Thickness",
            "Color",
            "Lengh",
            "Width",
            "Height",
            "Volume"
        ),
        "OFFERS_FIELD_CODE" => array("NAME", "PREVIEW_PICTURE", "CODE"),
        "OFFERS_PROPERTY_CODE" => array(
            "Size",
            "Color",

        ),
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_ORDER" => "asc",
        "DETAIL_URL" => "",
        "BASKET_URL" => "/personal/basket.php",
        "ACTION_VARIABLE" => "action",
        "PRODUCT_ID_VARIABLE" => "id",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "PRICE_CODE" => array(PRICE_CODE),
        "USE_PRICE_COUNT" => "Y",
        "SHOW_PRICE_COUNT" => "1",
        "PRICE_VAT_INCLUDE" => "Y",
        "DISPLAY_ELEMENT_SELECT_BOX" => "Y",
        "ELEMENT_SORT_FIELD_BOX" => "name",
        "ELEMENT_SORT_ORDER_BOX" => "asc",
        "ELEMENT_SORT_FIELD_BOX2" => "id",
        "ELEMENT_SORT_ORDER_BOX2" => "desc",
        "HIDE_NOT_AVAILABLE" => "N",
        "AJAX_OPTION_SHADOW" => "Y",
        "AJAX_OPTION_JUMP" => "Y",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "Y",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => CURRENCY_ID,
        "TEMPLATE_THEME" => "blue",
        "LINE_ELEMENT_COUNT" => $elemsInRow,
        "DEVICE_TYPE" => DEVICE_TYPE
    ]
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
