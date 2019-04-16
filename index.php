<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetTitle("Главная");

//Слайдер
$GLOBALS["arSliderFilter"] = [
    "!PREVIEW_PICTURE" => false,
    "!PREVIEW_TEXT" => false
];
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "slider-front",
    [
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "content",
        "IBLOCK_ID" => IBLOCK_CONTENT_SLIDER,
        "NEWS_COUNT" => "5",
        "SORT_BY1" => "ID",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arSliderFilter",
        "FIELD_CODE" => Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT"),
        "PROPERTY_CODE" => Array(),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "M d, Y",
        "SET_TITLE" => "N",
        "SET_BROWSER_TITLE" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_LAST_MODIFIED" => "Y",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "Y",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "PAGER_BASE_LINK_ENABLE" => "Y",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "Y",
        "MESSAGE_404" => "",
        "PAGER_BASE_LINK" => "",
        "PAGER_PARAMS_NAME" => "arrPager",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "SHOW_TITLE" => "Y",
        "SHOW_MORE" => "Y",
        //"IMAGE_SIZE" => []
    ]
);
//end

//разделы каталога
if (DEVICE_TYPE == "DESKTOP") {
    $itemsInRow = 5;
} elseif (DEVICE_TYPE == "TABLET") {
    $itemsInRow = 3;
} else {
    $itemsInRow = 1;
}
$arImageSize = ["WIDTH" => 175, "HEIGHT" => 116];
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "",
    [
        "VIEW_MODE" => "TEXT",
        "SHOW_PARENT_NAME" => "Y",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
        "SECTION_ID" => "",
        "SECTION_CODE" => "",
        "SECTION_URL" => "",
        "COUNT_ELEMENTS" => "Y",
        "TOP_DEPTH" => "1",
        "SECTION_FIELDS" => "",
        "SECTION_USER_FIELDS" => "",
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y",
        "ITEMS_IN_ROW" => $itemsInRow,
        "IMAGE_SIZE" => $arImageSize
    ]
);
//end

//Баннеры
$GLOBALS["arBannerFilter"] = [
    "!PROPERTY_LINK" => false,
    "CODE" => "index"
];
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "banner-front",
    [
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "content",
        "IBLOCK_ID" => IBLOCK_CONTENT_BANNER,
        "NEWS_COUNT" => "5",
        "SORT_BY1" => "ID",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "arBannerFilter",
        "FIELD_CODE" => Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT"),
        "PROPERTY_CODE" => Array("STYLE", "COLOR", "LINK"),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "M d, Y",
        "SET_TITLE" => "N",
        "SET_BROWSER_TITLE" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_LAST_MODIFIED" => "Y",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_DESC_NUMBERING" => "Y",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "PAGER_BASE_LINK_ENABLE" => "Y",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "Y",
        "MESSAGE_404" => "",
        "PAGER_BASE_LINK" => "",
        "PAGER_PARAMS_NAME" => "arrPager",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "SHOW_TITLE" => "Y",
        "SHOW_MORE" => "Y",
        //"IMAGE_SIZE" => []
    ]
);
//end

//топ-элементов каталога
$arCatalogTopParams = [
    "IMAGE_SIZE" => [
        "WIDTH" => "",
        "HEIGHT" => ""
    ],
    "ITEMS_COUNT" => 10,
    "LINE_ELEMENT_COUNT" => 5
];
if (DEVICE_TYPE == "TABLET") {
    $arCatalogTopParams["LINE_ELEMENT_COUNT"] = 3;
} elseif (DEVICE_TYPE == "MOBILE") {
    $arCatalogTopParams["LINE_ELEMENT_COUNT"] = 1;
}
$GLOBALS["arCatalogTopFilter"] = ["OFFERS" => ["PROPERTY_STATUS_VALUE" => "Акция"]];
$APPLICATION->IncludeComponent(
    "bitrix:catalog.top",
    "",
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
        "ELEMENT_COUNT" => $arCatalogTopParams["ITEMS_COUNT"],
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_FIELD2" => "id",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_SORT_ORDER2" => "desc",
        "ENLARGE_PRODUCT" => "STRICT",
        "FILTER_NAME" => "arCatalogTopFilter",
        "HIDE_NOT_AVAILABLE" => "N",
        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
        "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
        "IBLOCK_TYPE" => "catalog",
        "LABEL_PROP" => array("SALELEADER"),
        "LABEL_PROP_MOBILE" => array(),
        "LABEL_PROP_POSITION" => "top-left",
        "LINE_ELEMENT_COUNT" => $arCatalogTopParams["LINE_ELEMENT_COUNT"],
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
        "OFFERS_PROPERTY_CODE" => array("Size", "Color", "CML2_ARTICLE", "STATUS"),
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_ORDER2" => "desc",
        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
        "OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES"),
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array("Продажи СПб"),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_BLOCKS_ORDER" => "price,buttons",
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
        "TEMPLATE_THEME" => "blue",
        "USE_ENHANCED_ECOMMERCE" => "Y",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "Y",
        "VIEW_MODE" => "SECTION",
        "SHOW_PRODUCTS_".IBLOCK_CATALOG_CATALOGSKU => "Y",
        "DEVICE_TYPE" => DEVICE_TYPE,
        "IMAGE_SIZE" => $arCatalogTopParams["IMAGE_SIZE"],
        "ALL_LINK" => "/actions/"
    )
);
//end
?>

<section class="section banner_map">
    <div class="container">
        <div class="banner_map-item">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                [
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => SITE_TEMPLATE_PATH . "/include/index/about-company.php"
                ],
                false
            );?>
        </div>
        <?if (DEVICE_TYPE != "MOBILE") {
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.store.list",
                "front-map",
                Array(
                    "PHONE" => "Y",
                    "EMAIL" => "Y",
                    "SCHEDULE" => "Y",
                    "PATH_TO_ELEMENT" => "store/#store_id#",
                    "MAP_TYPE" => "0",
                    "SET_TITLE" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000"
                )
            );
        }?>
    </div>
</section>

<?
//Новости
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "news-front",
    [
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_MODE" => "N",
        "IBLOCK_TYPE" => "content",
        "IBLOCK_ID" => IBLOCK_CONTENT_NEWS,
        "NEWS_COUNT" => "3",
        "SORT_BY1" => "ACTIVE_DATE",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "",
        "FIELD_CODE" => Array("ID", "NAME"),
        "PROPERTY_CODE" => Array(),
        "CHECK_DATES" => "N",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "N",
        "SET_BROWSER_TITLE" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_LAST_MODIFIED" => "Y",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "PAGER_BASE_LINK_ENABLE" => "Y",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "Y",
        "MESSAGE_404" => "",
        "PAGER_BASE_LINK" => "",
        "PAGER_PARAMS_NAME" => "arrPager",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "ITEMS_IN_ROW" => (DEVICE_TYPE == "DESKTOP" ? 3 : 1),
    ]
);
//end
?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>