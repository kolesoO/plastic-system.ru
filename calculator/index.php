<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Калькулятор складских лотков");
$APPLICATION->SetPageProperty("header_section-class", "section");

if (DEVICE_TYPE == "MOBILE") {
    $tmp = "calculator-mobile";
} else {
    $tmp = "calculator";
}

$APPLICATION->IncludeComponent(
    "bitrix:catalog.top",
    $tmp,
    Array(
        "ACTION_VARIABLE" => "action",
        "ADD_PICT_PROP" => "MORE_PHOTO",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "ADD_TO_BASKET_ACTION" => "ADD",
        "BASKET_URL" => "/personal/basket.php",
        "BRAND_PROPERTY" => "BRAND_REF",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
        "COMPARE_PATH" => "",
        "COMPATIBLE_MODE" => "N",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "CUSTOM_FILTER" => "",
        "DATA_LAYER_NAME" => "dataLayer",
        "DETAIL_URL" => "",
        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
        "DISPLAY_COMPARE" => "N",
        "ELEMENT_COUNT" => "10",
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_FIELD2" => "id",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_SORT_ORDER2" => "desc",
        "ENLARGE_PRODUCT" => "STRICT",
        "FILTER_NAME" => "",
        "HIDE_NOT_AVAILABLE" => "N",
        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
        "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
        "IBLOCK_TYPE" => "catalog",
        "LABEL_PROP" => array("SALELEADER"),
        "LABEL_PROP_MOBILE" => array(),
        "LABEL_PROP_POSITION" => "top-left",
        "LINE_ELEMENT_COUNT" => "",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_COMPARE" => "Сравнить",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_NOT_AVAILABLE" => "Нет в наличии",
        "MESS_RELATIVE_QUANTITY_FEW" => "мало",
        "MESS_RELATIVE_QUANTITY_MANY" => "много",
        "MESS_SHOW_MAX_QUANTITY" => "Наличие",
        "OFFERS_CART_PROPERTIES" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
        "OFFERS_FIELD_CODE" => array("ID", "NAME", "PREVIEW_PICTURE", "CODE"),
        "OFFERS_LIMIT" => "0",
        "OFFERS_PROPERTY_CODE" => array("RAZMER", "TSVET", "CML2_ARTICLE", "STATUS"),
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_ORDER2" => "desc",
        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
        "OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES"),
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array("Продажи СПб"),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPERTIES" => array("NEWPRODUCT"),
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "",
        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
        "PRODUCT_SUBSCRIPTION" => "Y",
        "PROPERTY_CODE" => array(),
        "PROPERTY_CODE_MOBILE" => array(),
        "RELATIVE_QUANTITY_FACTOR" => "5",
        "ROTATE_TIMER" => "30",
        "SECTION_URL" => "",
        "SEF_MODE" => "N",
        "SEF_RULE" => "",
        "SHOW_CLOSE_POPUP" => "N",
        "SHOW_DISCOUNT_PERCENT" => "Y",
        "SHOW_MAX_QUANTITY" => "M",
        "SHOW_OLD_PRICE" => "Y",
        "SHOW_PAGINATION" => "Y",
        "SHOW_PRICE_COUNT" => "1",
        "SHOW_SLIDER" => "Y",
        "SLIDER_INTERVAL" => "3000",
        "SLIDER_PROGRESS" => "N",
        "TEMPLATE_THEME" => "",
        "USE_ENHANCED_ECOMMERCE" => "Y",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "Y",
        "VIEW_MODE" => "SECTION",
        "SHOW_PRODUCTS_".IBLOCK_CATALOG_CATALOGSKU => "Y",
        "WRAP_ID" => "catalog-calculator-wrap",
        "AJAX_TEMPLATE" => "ajax"
        //"IMAGE_SIZE" => []
    )
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');