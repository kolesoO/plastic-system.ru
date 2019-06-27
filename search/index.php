<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", "Поиск - Складские лотки и пластиковая тара от компании ООО «Пластик Система»");
$APPLICATION->SetTitle("Поиск");
$APPLICATION->SetPageProperty("header_section-class", "section");

//подготовка парметров компонента
if (DEVICE_TYPE == "DESKTOP") {
    $itemsInRow = 5;
    $itemsInRowInner = 4;
    $elemsInRow = 4;
    $pageElemCount = 12;
    $pagerTmp = ".default";
} elseif (DEVICE_TYPE == "TABLET") {
    $itemsInRow = 3;
    $itemsInRowInner = 2;
    $elemsInRow = 2;
    $pageElemCount = 12;
    $pagerTmp = ".default-mobile";
} else {
    $itemsInRow = $itemsInRowInner = $elemsInRow = 1;
    $pageElemCount = 10;
    $pagerTmp = ".default-mobile";
}
$arImageSize = ["WIDTH" => 175, "HEIGHT" => 116];
//end

$APPLICATION->IncludeComponent (
    "bitrix:catalog.search",
    "",
    Array(
        "AJAX_MODE" => "Y",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
        "ELEMENT_SORT_FIELD" => (isset($_GET["sort_by"]) ? $_GET["sort_by"] : "PROPERTY_CML2_ARTICLE"),
        "ELEMENT_SORT_ORDER" => (isset($_GET["sort_by"]) ? $_GET["sort_order"] : "asc"),
        "ELEMENT_SORT_FIELD2" => "id",
        "ELEMENT_SORT_ORDER2" => "desc",
        "SECTION_URL" => "",
        "DETAIL_URL" => "",
        "BASKET_URL" => "/personal/basket.php",
        "ACTION_VARIABLE" => "action",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "DISPLAY_COMPARE" => "Y",
        "PAGE_ELEMENT_COUNT" => $pageElemCount,
        "LINE_ELEMENT_COUNT" => $elemsInRow,
        "PROPERTY_CODE" => array("TSVET", "RAZMER", "CML2_ARTICLE", "STATUS"),
        "OFFERS_FIELD_CODE" => array(
            0 => "NAME",
            1 => "PREVIEW_PICTURE",
            2 => "CODE",
        ),
        "OFFERS_PROPERTY_CODE" => array("TSVET", "RAZMER", "CML2_ARTICLE", "STATUS"),
        "OFFERS_SORT_FIELD" => "PROPERTY_CML2_ARTICLE",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_FIELD2" => "sort",
        "OFFERS_SORT_ORDER2" => "asc",
        "OFFERS_LIMIT" => "0",
        "PRICE_CODE" => array(PRICE_CODE),
        "USE_PRICE_COUNT" => "Y",
        "SHOW_PRICE_COUNT" => "1",
        "PRICE_VAT_INCLUDE" => "Y",
        "USE_PRODUCT_QUANTITY" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "RESTART" => "Y",
        "NO_WORD_LOGIC" => "Y",
        "USE_LANGUAGE_GUESS" => "N",
        "CHECK_DATES" => "Y",
        "DISPLAY_TOP_PAGER" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Товары",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => $pagerTmp,
        "PAGER_DESC_NUMBERING" => "Y",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "HIDE_NOT_AVAILABLE" => "N",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "OFFERS_CART_PROPERTIES" => array(),
        "AJAX_OPTION_JUMP" => "Y",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "Y",
        "ELEMENT_IMAGE_SIZE" => $arImageSize,
        "DEVICE_TYPE" => DEVICE_TYPE,
    )
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');