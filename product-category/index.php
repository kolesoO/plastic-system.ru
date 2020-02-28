<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "Санкт-Петербург Адрес склада и офиса продаж: Санкт-Петербург, ул. Рабочая 16Г Телефон: +7 (812) 313-75-50 E-mail: mail@plsm.ru Режим работы: Пн. — Чт.: 9:00 — 17:30, Пт.— до 17:00, Сб. — Вс.: выходной Москва Адрес склада: МО, г. Дзержинский, ул. Алексеевская, д. 2 Телефон: +7 (495) 162-75-50 E-mail: msk@plsm.ru Режим работы: Пн. — Чт.: 9:00 — 17:30, Пт.— до 17:00, Сб. — Вс.: [&hellip;]");
$APPLICATION->SetPageProperty("title", "Контакты - Складские лотки и пластиковая тара от компании ООО «Пластик Система»");
$APPLICATION->SetTitle("Каталог");

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
$arDetailSize = ["WIDTH" => 557, "HEIGHT" => 366];
$sefUrlTpl = [
    "sections" => "",
    "section" => "#SECTION_CODE_PATH#/",
    "element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
    "compare" => "/compare/"
];
$arCurPage = explode("/", trim($strCurPage, "/"));
if (strpos(end($arCurPage), \kDevelop\Help\Tools::getOfferPrefixInUrl()) !== false) {
    $sefUrlTpl["element"] = "#SECTION_CODE_PATH#/#ELEMENT_CODE#/".\kDevelop\Help\Tools::getOfferSefUrlTmp()."/";
}
unset($arCurPage);
//end

$GLOBALS["arCatalogFilter"] = ["!OFFERS" => null];
$APPLICATION->IncludeComponent(
    "bitrix:catalog",
    "",
    Array(
        "TEMPLATE_THEME" => "blue",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
        "HIDE_NOT_AVAILABLE" => "N",
        "BASKET_URL" => "/personal/cart/",
        "ACTION_VARIABLE" => "action",
        "PRODUCT_ID_VARIABLE" => "id",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PARTIAL_PRODUCT_PROPERTIES" => "Y",
        "COMMON_SHOW_CLOSE_POPUP" => "N",
        "SEF_MODE" => "Y",
        "SEF_FOLDER" => "/product-category/",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "USE_MAIN_ELEMENT_SECTION" => "Y",
        "SET_LAST_MODIFIED" => "N",
        "SET_TITLE" => "N",
        "ADD_SECTIONS_CHAIN" => "Y",
        "ADD_ELEMENT_CHAIN" => "Y",
        "USE_ELEMENT_COUNTER" => "Y",
        "USE_SALE_BESTSELLERS" => "Y",
        "COMPARE_POSITION_FIXED" => "Y",
        "COMPARE_POSITION" => "top left",
        "USE_FILTER" => "Y",
        "FILTER_NAME" => "arCatalogFilter",
        "FILTER_FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "FILTER_PROPERTY_CODE" => array(
            0 => "",
            1 => "",
        ),
        "FILTER_PRICE_CODE" => array(
            0 => PRICE_CODE,
        ),
        "FILTER_OFFERS_FIELD_CODE" => array(
            0 => "PREVIEW_PICTURE",
            1 => "DETAIL_PICTURE",
            2 => "",
        ),
        "FILTER_OFFERS_PROPERTY_CODE" => array(
            0 => "",
            1 => "",
        ),
        "USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
        "TOP_ADD_TO_BASKET_ACTION" => "ADD",
        "SECTION_ADD_TO_BASKET_ACTION" => "ADD",
        "DETAIL_ADD_TO_BASKET_ACTION" => array("BUY"),
        "DETAIL_SHOW_BASIS_PRICE" => "Y",
        "FILTER_VIEW_MODE" => "VERTICAL",
        "USE_REVIEW" => "Y",
        "MESSAGES_PER_PAGE" => "10",
        "USE_CAPTCHA" => "Y",
        "REVIEW_AJAX_POST" => "Y",
        "PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
        "FORUM_ID" => "1",
        "URL_TEMPLATES_READ" => "",
        "SHOW_LINK_TO_FORUM" => "Y",
        "POST_FIRST_MESSAGE" => "N",
        "USE_COMPARE" => "Y",
        "PRICE_CODE" => array(
            0 => PRICE_CODE,
        ),
        "USE_PRICE_COUNT" => "N",
        "SHOW_PRICE_COUNT" => "1",
        "PRICE_VAT_INCLUDE" => "Y",
        "PRICE_VAT_SHOW_VALUE" => "N",
        "PRODUCT_PROPERTIES" => array(
        ),
        "USE_PRODUCT_QUANTITY" => "Y",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "OFFERS_CART_PROPERTIES" => array(
            0 => "COLOR_REF",
            1 => "SIZES_SHOES",
            2 => "SIZES_CLOTHES",
        ),
        "SHOW_TOP_ELEMENTS" => "N",
        "SECTION_COUNT_ELEMENTS" => "N",
        "SECTION_TOP_DEPTH" => "1",
        "SECTIONS_VIEW_MODE" => "TEXT",
        "SECTIONS_SHOW_PARENT_NAME" => "Y",
        "PAGE_ELEMENT_COUNT" => $pageElemCount,
        "LINE_ELEMENT_COUNT" => $elemsInRow,
        "ELEMENT_SORT_FIELD" => (isset($_GET["sort_by"]) ? $_GET["sort_by"] : "sort"),
        "ELEMENT_SORT_ORDER" => (isset($_GET["sort_by"]) ? $_GET["sort_order"] : "asc"),
        "ELEMENT_SORT_FIELD2" => "PROPERTY_CML2_ARTICLE",
        "ELEMENT_SORT_ORDER2" => "asc",
        "LIST_PROPERTY_CODE" => array("is_main"),
        "INCLUDE_SUBSECTIONS" => "Y",
        "LIST_META_KEYWORDS" => "UF_KEYWORDS",
        "LIST_META_DESCRIPTION" => "UF_META_DESCRIPTION",
        "LIST_BROWSER_TITLE" => "UF_BROWSER_TITLE",
        "LIST_OFFERS_FIELD_CODE" => array(
            0 => "NAME",
            1 => "PREVIEW_PICTURE",
            2 => "CODE",
        ),
        "LIST_OFFERS_PROPERTY_CODE" => array("RAZMER", "TSVET", "CML2_ARTICLE", "STATUS"),
        "LIST_OFFERS_LIMIT" => "0",
        "SECTION_BACKGROUND_IMAGE" => "-",
        "DETAIL_DETAIL_PICTURE_MODE" => "IMG",
        "DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
        "DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
        "DETAIL_PROPERTY_CODE" => array("*"),
        "DETAIL_META_KEYWORDS" => "KEYWORDS",
        "DETAIL_META_DESCRIPTION" => "META_DESCRIPTION",
        "DETAIL_BROWSER_TITLE" => "",
        "DETAIL_SET_CANONICAL_URL" => "N",
        "DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
        "SHOW_DEACTIVATED" => "N",
        "DETAIL_OFFERS_FIELD_CODE" => array(
            0 => "NAME",
            1 => "DETAIL_PICTURE",
            2 => "CODE",
            3 => "DETAIL_TEXT"
        ),
        "DETAIL_OFFERS_PROPERTY_CODE" => array("*"),
        "DETAIL_BACKGROUND_IMAGE" => "-",
        "DETAIL_STRICT_SECTION_CHECK" => "Y",
        "LINK_IBLOCK_TYPE" => "catalog",
        "LINK_IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU,
        "LINK_PROPERTY_SID" => "",
        "LINK_ELEMENTS_URL" => "offer-#ELEMENT_CODE#/",
        "USE_ALSO_BUY" => "Y",
        "ALSO_BUY_ELEMENT_COUNT" => "3",
        "ALSO_BUY_MIN_BUYES" => "2",
        "DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
        "USE_GIFTS_DETAIL" => "Y",
        "USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
        "USE_GIFTS_SECTION" => "Y",
        "GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
        "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
        "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
        "GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
        "GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
        "GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
        "GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
        "GIFTS_MESS_BTN_BUY" => "Выбрать",
        "GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
        "GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
        "GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
        "GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
        "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
        "GIFTS_SHOW_IMAGE" => "Y",
        "GIFTS_SHOW_NAME" => "Y",
        "GIFTS_SHOW_OLD_PRICE" => "Y",
        "USE_STORE" => "Y",
        "STORES" => array(STORE_ID),
        "USE_MIN_AMOUNT" => "N",
        "USER_FIELDS" => array(""),
        "FIELDS" => array("ADDRESS", "PHONE"),
        "SHOW_EMPTY_STORE" => "Y",
        "SHOW_GENERAL_STORE_INFORMATION" => "N",
        "STORE_PATH" => "/store/#store_id#",
        "MAIN_TITLE" => "Наличие на складах",
        "USE_BIG_DATA" => "Y",
        "BIG_DATA_RCM_TYPE" => "bestsell",
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_FIELD2" => "PROPERTY_CML2_ARTICLE",
        "OFFERS_SORT_ORDER2" => "asc",
        "PAGER_TEMPLATE" => $pagerTmp,
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Товары",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_BASE_LINK_ENABLE" => "Y",
        "PAGER_BASE_LINK" => "",
        "PAGER_PARAMS_NAME" => "arrPager",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "N",
        "MESSAGE_404" => "",
        "ADD_PICT_PROP" => "-",
        "LABEL_PROP" => "NEWPRODUCT",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
        "OFFER_TREE_PROPS" => array("RAZMER", "TSVET", "CML2_ARTICLE", "STATUS"),
        "DETAIL_DISPLAY_NAME" => "Y",
        "DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
        "SHOW_DISCOUNT_PERCENT" => "Y",
        "SHOW_OLD_PRICE" => "Y",
        "DETAIL_SHOW_MAX_QUANTITY" => "N",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_COMPARE" => "Сравнение",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_NOT_AVAILABLE" => "Нет в наличии",
        "TOP_VIEW_MODE" => "SECTION",
        "DETAIL_USE_VOTE_RATING" => "Y",
        "DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",
        "DETAIL_USE_COMMENTS" => "Y",
        "DETAIL_BLOG_USE" => "Y",
        "DETAIL_VK_USE" => "N",
        "DETAIL_FB_USE" => "Y",
        "DETAIL_FB_APP_ID" => "",
        "DETAIL_BRAND_USE" => "N",
        "SIDEBAR_SECTION_SHOW" => "Y",
        "SIDEBAR_DETAIL_SHOW" => "N",
        "SIDEBAR_PATH" => "/examples/index_inc.php",
        "AJAX_OPTION_ADDITIONAL" => "",
        "SEF_URL_TEMPLATES" => $sefUrlTpl,
        "SECTIONS_ITEMS_IN_ROW" => $itemsInRow,
        "SECTION_ITEMS_IN_ROW" => $itemsInRowInner,
        "SECTIONS_IMAGE_SIZE" => $arImageSize,
        "ELEMENT_IMAGE_SIZE" => $arImageSize,
        "DETAIL_IMAGE_SIZE" => $arDetailSize,
        "DEVICE_TYPE" => DEVICE_TYPE,
        "INSTANT_RELOAD" => true
    ),
    false
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');