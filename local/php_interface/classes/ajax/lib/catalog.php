<?
namespace kDevelop\Ajax;

class Catalog
{
    /** @var array */
    private static $arDefIbFields = ["ID", "IBLOCK_ID", "NAME"];

    /**
     * @param array $arSort
     * @param array $arFilter
     * @param array $arFieldCode
     * @param array $arPropCode
     * @return array|bool|mixed
     */
    private static function getIblockElement(array $arSort, array $arFilter, array $arFieldCode, array $arPropCode)
    {
        if (\Bitrix\Main\Loader::includeModule("iblock")) {
            $arReturn = [];
            $rsItems = \CIblockElement::GetList(
                $arSort,
                $arFilter,
                false,
                false,
                array_unique(
                    array_merge(self::$arDefIbFields, $arFieldCode)
                )
            );
            while ($rsItem = $rsItems->GetNextElement()) {
                $arItem = $rsItem->getFields();
                $arProps = $rsItem->getProperties();
                foreach ($arProps as $code => $arPropItem) {
                    if (!in_array($code, $arPropCode)) continue;
                    $arItem["PROPERTIES"][$code] = $arPropItem;
                }
                $arReturn[] = $arItem;
            }
            return count($arReturn) > 1 ? $arReturn : $arReturn[0];
        }

        return false;
    }

    /**
     * @param $convertCurrency
     * @param $curId
     * @return array
     */
    private static function getCurrencyConvert($convertCurrency, $curId)
    {
        $return = [];
        if ($convertCurrency === 'Y') {
            $correct = false;
            if (\Bitrix\Main\Loader::includeModule('currency')) {
                $correct = \Bitrix\Currency\CurrencyManager::isCurrencyExist($curId);
            }
            if ($correct) {
                $return = ['CURRENCY_ID' => $curId];
            }
        }

        return $return;
    }

    /**
     * @param $arFullParams
     * @return array
     */
    public static function getCatalogItem($arFullParams)
    {
        global $APPLICATION;

        $arParams = $arFullParams['PARAMS'];
        $componentElementParams = array(
            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'PROPERTY_CODE' => (isset($arParams['PROPERTY_CODE']) ? $arParams['PROPERTY_CODE'] : []),
            'META_KEYWORDS' => $arParams['META_KEYWORDS'],
            'META_DESCRIPTION' => $arParams['META_DESCRIPTION'],
            'BROWSER_TITLE' => $arParams['BROWSER_TITLE'],
            'SET_CANONICAL_URL' => $arParams['SET_CANONICAL_URL'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
            'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
            'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
            'CHECK_SECTION_ID_VARIABLE' => (isset($arParams['CHECK_SECTION_ID_VARIABLE']) ? $arParams['CHECK_SECTION_ID_VARIABLE'] : ''),
            'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
            'CACHE_TIME' => $arParams['CACHE_TIME'],
            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
            'SET_TITLE' => $arParams["SET_TITLE"],
            'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
            'MESSAGE_404' => $arParams['~MESSAGE_404'],
            'SET_STATUS_404' => $arParams['SET_STATUS_404'],
            'SHOW_404' => $arParams['SHOW_404'],
            'FILE_404' => $arParams['FILE_404'],
            'PRICE_CODE' => $arParams['PRICE_CODE'],
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
            'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
            'PRICE_VAT_SHOW_VALUE' => $arParams['PRICE_VAT_SHOW_VALUE'],
            'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'PRODUCT_PROPERTIES' => (isset($arParams['PRODUCT_PROPERTIES']) ? $arParams['PRODUCT_PROPERTIES'] : []),
            'ADD_PROPERTIES_TO_BASKET' => (isset($arParams['ADD_PROPERTIES_TO_BASKET']) ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
            'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
            'LINK_IBLOCK_TYPE' => $arParams['LINK_IBLOCK_TYPE'],
            'LINK_IBLOCK_ID' => $arParams['LINK_IBLOCK_ID'],
            'LINK_PROPERTY_SID' => $arParams['LINK_PROPERTY_SID'],
            'LINK_ELEMENTS_URL' => $arParams['LINK_ELEMENTS_URL'],

            'OFFERS_CART_PROPERTIES' => (isset($arParams['OFFERS_CART_PROPERTIES']) ? $arParams['OFFERS_CART_PROPERTIES'] : []),
            'OFFERS_FIELD_CODE' => $arParams['OFFERS_FIELD_CODE'],
            'OFFERS_PROPERTY_CODE' => (isset($arParams['OFFERS_PROPERTY_CODE']) ? $arParams['OFFERS_PROPERTY_CODE'] : []),
            'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
            'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
            'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
            'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],

            'ELEMENT_ID' => $arFullParams['item_id'],
            'ELEMENT_CODE' => '',
            'SECTION_ID' => '',
            'SECTION_CODE' => '',
            'SECTION_URL' => '',
            'DETAIL_URL' => '',
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
            'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
            'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
            'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
            'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],
            'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
            'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
            'LABEL_PROP' => $arParams['LABEL_PROP'],
            'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
            'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
            'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
            'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
            'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
            'DISCOUNT_PERCENT_POSITION' => (isset($arParams['DISCOUNT_PERCENT_POSITION']) ? $arParams['DISCOUNT_PERCENT_POSITION'] : ''),
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
            'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
            'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
            'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
            'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
            'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
            'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
            'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
            'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
            'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),
            'MESS_PRICE_RANGES_TITLE' => (isset($arParams['~MESS_PRICE_RANGES_TITLE']) ? $arParams['~MESS_PRICE_RANGES_TITLE'] : ''),
            'MESS_DESCRIPTION_TAB' => (isset($arParams['~MESS_DESCRIPTION_TAB']) ? $arParams['~MESS_DESCRIPTION_TAB'] : ''),
            'MESS_PROPERTIES_TAB' => (isset($arParams['~MESS_PROPERTIES_TAB']) ? $arParams['~MESS_PROPERTIES_TAB'] : ''),
            'MESS_COMMENTS_TAB' => (isset($arParams['~MESS_COMMENTS_TAB']) ? $arParams['~MESS_COMMENTS_TAB'] : ''),
            'MAIN_BLOCK_PROPERTY_CODE' => (isset($arParams['DETAIL_MAIN_BLOCK_PROPERTY_CODE']) ? $arParams['DETAIL_MAIN_BLOCK_PROPERTY_CODE'] : ''),
            'MAIN_BLOCK_OFFERS_PROPERTY_CODE' => (isset($arParams['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE']) ? $arParams['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE'] : ''),
            'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
            'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
            'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
            'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
            'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
            'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
            'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
            'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
            'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
            'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
            'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
            'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
            'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
            'IMAGE_RESOLUTION' => (isset($arParams['DETAIL_IMAGE_RESOLUTION']) ? $arParams['DETAIL_IMAGE_RESOLUTION'] : ''),
            'PRODUCT_INFO_BLOCK_ORDER' => (isset($arParams['DETAIL_PRODUCT_INFO_BLOCK_ORDER']) ? $arParams['DETAIL_PRODUCT_INFO_BLOCK_ORDER'] : ''),
            'PRODUCT_PAY_BLOCK_ORDER' => (isset($arParams['DETAIL_PRODUCT_PAY_BLOCK_ORDER']) ? $arParams['DETAIL_PRODUCT_PAY_BLOCK_ORDER'] : ''),
            'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
            'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
            'ADD_SECTIONS_CHAIN' => (isset($arParams['ADD_SECTIONS_CHAIN']) ? $arParams['ADD_SECTIONS_CHAIN'] : ''),
            'ADD_ELEMENT_CHAIN' => (isset($arParams['ADD_ELEMENT_CHAIN']) ? $arParams['ADD_ELEMENT_CHAIN'] : ''),
            'DISPLAY_PREVIEW_TEXT_MODE' => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
            'DETAIL_PICTURE_MODE' => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : array()),
            'ADD_TO_BASKET_ACTION' => isset($basketAction) ? $basketAction : '',
            'ADD_TO_BASKET_ACTION_PRIMARY' => (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY'] : null),
            'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
            'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
            'COMPARE_PATH' => '',
            'USE_COMPARE_LIST' => 'Y',
            'BACKGROUND_IMAGE' => (isset($arParams['DETAIL_BACKGROUND_IMAGE']) ? $arParams['DETAIL_BACKGROUND_IMAGE'] : ''),
            'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
            'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
            'SET_VIEWED_IN_COMPONENT' => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),
            'SHOW_SLIDER' => (isset($arParams['DETAIL_SHOW_SLIDER']) ? $arParams['DETAIL_SHOW_SLIDER'] : ''),
            'SLIDER_INTERVAL' => (isset($arParams['DETAIL_SLIDER_INTERVAL']) ? $arParams['DETAIL_SLIDER_INTERVAL'] : ''),
            'SLIDER_PROGRESS' => (isset($arParams['DETAIL_SLIDER_PROGRESS']) ? $arParams['DETAIL_SLIDER_PROGRESS'] : ''),
            'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
            'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
            'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

            'USE_GIFTS_DETAIL' => $arParams['USE_GIFTS_DETAIL']?: 'Y',
            'USE_GIFTS_MAIN_PR_SECTION_LIST' => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST']?: 'Y',
            'GIFTS_SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
            'GIFTS_SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
            'GIFTS_DETAIL_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
            'GIFTS_DETAIL_HIDE_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
            'GIFTS_DETAIL_TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
            'GIFTS_DETAIL_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
            'GIFTS_SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
            'GIFTS_SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
            'GIFTS_MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
            'GIFTS_PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
            'GIFTS_SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
            'GIFTS_SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
            'GIFTS_SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

            'GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
            'GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],
            'GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'],

            "OFFER_CODE_SELECTED" => '',

            "IMAGE_SIZE" => $arParams["IMAGE_SIZE"],
            "DEVICE_TYPE" => $arParams["DEVICE_TYPE"]
        );

        if (isset($arParams['USER_CONSENT']))
        {
            $componentElementParams['USER_CONSENT'] = $arParams['USER_CONSENT'];
        }

        if (isset($arParams['USER_CONSENT_ID']))
        {
            $componentElementParams['USER_CONSENT_ID'] = $arParams['USER_CONSENT_ID'];
        }

        if (isset($arParams['USER_CONSENT_IS_CHECKED']))
        {
            $componentElementParams['USER_CONSENT_IS_CHECKED'] = $arParams['USER_CONSENT_IS_CHECKED'];
        }

        if (isset($arParams['USER_CONSENT_IS_LOADED']))
        {
            $componentElementParams['USER_CONSENT_IS_LOADED'] = $arParams['USER_CONSENT_IS_LOADED'];
        }

        $componentElementParams['OFFER_ID_SELECTED'] = $arFullParams['offer_id'];

        ob_start();
        $APPLICATION->IncludeComponent(
            'bitrix:catalog.element',
            'preview',
            $componentElementParams,
            null
        );
        $return = ob_get_clean();

        return ["js_callback" => "getCatalogItemCallBack", "html" => $return];
    }

    /**
     * @param $arParams
     * @return array
     */
    public static function getCatalogCalcItems($arParams)
    {
        global $APPLICATION;
        global $arCatalogFilter;

        $return = "";

        if (isset($arParams["AJAX_TEMPLATE"]) && strlen($arParams["AJAX_TEMPLATE"]) > 0) {
            //filter
            foreach ($arParams["FILTER_VALUES"] as $key => $filterValue) {
                if(strlen($filterValue) == 0) {
                    unset($arParams["FILTER_VALUES"][$key]);
                }
            }
            $arCatalogFilter = [];
            if (is_array($arParams["FILTER_VALUES"]) ? $arParams["FILTER_VALUES"] : []) {
                foreach ($arParams["FILTER_VALUES"] as $code => $value) {
                    if (floatval($value) == 0 || strpos($code, "PROPERTY_") !== 0) continue;
                    $arCatalogFilter["<=" . $code] = $value;
                    /*$arCatalogFilter[] = [
                        "LOGIC" => "AND",
                        [">=" . $code => $value],
                        ["!" . $code => false]
                    ];*/
                }
            }
            $arCatalogFilter["!OFFERS"] = null;
            //end

            ob_start();
            if (intval($arParams["SECTION_ID"]) > 0) {
                $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    $arParams["AJAX_TEMPLATE"],
                    [
                        "ACTION_VARIABLE" => "action",
                        "ADD_PICT_PROP" => "MORE_PHOTO",
                        "ADD_PROPERTIES_TO_BASKET" => "Y",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "ADD_TO_BASKET_ACTION" => "ADD",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
                        "BASKET_URL" => "/personal/basket.php",
                        "BRAND_PROPERTY" => "BRAND_REF",
                        "BROWSER_TITLE" => "-",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "0",
                        "CACHE_TYPE" => "N",
                        "COMPATIBLE_MODE" => "Y",
                        "CONVERT_CURRENCY" => "Y",
                        "CURRENCY_ID" => "RUB",
                        "CUSTOM_FILTER" => "",
                        "DATA_LAYER_NAME" => "dataLayer",
                        "DETAIL_URL" => "",
                        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_TOP_PAGER" => "N",
                        "ELEMENT_SORT_FIELD" => "sort",
                        "ELEMENT_SORT_FIELD2" => "id",
                        "ELEMENT_SORT_ORDER" => "asc",
                        "ELEMENT_SORT_ORDER2" => "desc",
                        "ENLARGE_PRODUCT" => "PROP",
                        "ENLARGE_PROP" => "NEWPRODUCT",
                        "FILTER_NAME" => "arCatalogFilter",
                        "HIDE_NOT_AVAILABLE" => "N",
                        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                        "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
                        "IBLOCK_TYPE" => "catalog",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "LABEL_PROP" => array("NEWPRODUCT"),
                        "LABEL_PROP_MOBILE" => array(),
                        "LABEL_PROP_POSITION" => "top-left",
                        "LAZY_LOAD" => "Y",
                        "LINE_ELEMENT_COUNT" => "3",
                        "LOAD_ON_SCROLL" => "N",
                        "MESSAGE_404" => "",
                        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                        "MESS_BTN_BUY" => "Купить",
                        "MESS_BTN_DETAIL" => "Подробнее",
                        "MESS_BTN_LAZY_LOAD" => "Показать ещё",
                        "MESS_BTN_SUBSCRIBE" => "Подписаться",
                        "MESS_NOT_AVAILABLE" => "Нет в наличии",
                        "META_DESCRIPTION" => "-",
                        "META_KEYWORDS" => "-",
                        "OFFERS_CART_PROPERTIES" => array("ARTNUMBER","COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
                        "OFFERS_FIELD_CODE" => array("ID", "NAME", "PREVIEW_PICTURE", "CODE"),
                        "OFFERS_LIMIT" => "0",
                        "OFFERS_PROPERTY_CODE" => array("RAZMER", "TSVET", "CML2_ARTICLE", "STATUS"),
                        "OFFERS_SORT_FIELD" => "sort",
                        "OFFERS_SORT_FIELD2" => "id",
                        "OFFERS_SORT_ORDER" => "asc",
                        "OFFERS_SORT_ORDER2" => "desc",
                        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                        "OFFER_TREE_PROPS" => array("COLOR_REF","SIZES_SHOES","SIZES_CLOTHES"),
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "PAGER_TITLE" => "Товары",
                        "PAGE_ELEMENT_COUNT" => "10",
                        "PARTIAL_PRODUCT_PROPERTIES" => "N",
                        "PRICE_CODE" => array(PRICE_CODE),
                        "PRICE_VAT_INCLUDE" => "Y",
                        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
                        "PRODUCT_DISPLAY_MODE" => "Y",
                        "PRODUCT_ID_VARIABLE" => "id",
                        "PRODUCT_PROPERTIES" => array("NEWPRODUCT","MATERIAL"),
                        "PRODUCT_PROPS_VARIABLE" => "prop",
                        "PRODUCT_QUANTITY_VARIABLE" => "",
                        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
                        "PRODUCT_SUBSCRIPTION" => "Y",
                        "PROPERTY_CODE" => array("DLINA_MM","SHIRINA_MM", "VYSOTA_MM"),
                        "PROPERTY_CODE_MOBILE" => array(),
                        "RCM_PROD_ID" => "",
                        "RCM_TYPE" => "personal",
                        "SECTION_CODE" => "",
                        "SECTION_ID" => $arParams["SECTION_ID"],
                        "SECTION_ID_VARIABLE" => "SECTION_ID",
                        "SECTION_URL" => "",
                        "SECTION_USER_FIELDS" => array("",""),
                        "SEF_MODE" => "Y",
                        "SET_BROWSER_TITLE" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_STATUS_404" => "N",
                        "SET_TITLE" => "N",
                        "SHOW_404" => "N",
                        "SHOW_ALL_WO_SECTION" => "Y",
                        "SHOW_CLOSE_POPUP" => "N",
                        "SHOW_DISCOUNT_PERCENT" => "Y",
                        "SHOW_FROM_SECTION" => "N",
                        "SHOW_MAX_QUANTITY" => "N",
                        "SHOW_OLD_PRICE" => "N",
                        "SHOW_PRICE_COUNT" => "1",
                        "SHOW_SLIDER" => "Y",
                        "SLIDER_INTERVAL" => "3000",
                        "SLIDER_PROGRESS" => "N",
                        "TEMPLATE_THEME" => "blue",
                        "USE_ENHANCED_ECOMMERCE" => "Y",
                        "USE_MAIN_ELEMENT_SECTION" => "N",
                        "USE_PRICE_COUNT" => "N",
                        "USE_PRODUCT_QUANTITY" => "N"
                        //"IMAGE_SIZE" => []
                    ]
                );
            }
            $return = ob_get_contents();
            ob_end_clean();
        }

        return ["js_callback" => "getCatalogCalcItemsCallBack", "html" => $return];
    }

    /**
     * @param $arParams
     * @return array
     */
    public function getCatalogElement($arParams)
    {
        global $APPLICATION;

        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.element",
            "ajax",
            Array(
                "ACTION_VARIABLE" => "action",
                "ADD_DETAIL_TO_SLIDER" => "N",
                "ADD_ELEMENT_CHAIN" => "N",
                "ADD_PICT_PROP" => "-",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "ADD_SECTIONS_CHAIN" => "Y",
                "ADD_TO_BASKET_ACTION" => array("BUY"),
                "ADD_TO_BASKET_ACTION_PRIMARY" => array("BUY"),
                "BACKGROUND_IMAGE" => "-",
                "BASKET_URL" => "/personal/basket.php",
                "BLOG_USE" => "N",
                "BRAND_PROPERTY" => "BRAND_REF",
                "BRAND_PROP_CODE" => array("BRAND_REF", ""),
                "BRAND_USE" => "N",
                "BROWSER_TITLE" => "-",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "CHECK_SECTION_ID_VARIABLE" => "N",
                "COMPARE_PATH" => "/compare/",
                "COMPATIBLE_MODE" => "Y",
                "CONVERT_CURRENCY" => "Y",
                "CURRENCY_ID" => "RUB",
                "DATA_LAYER_NAME" => "dataLayer",
                "DETAIL_PICTURE_MODE" => array("POPUP", "MAGNIFIER"),
                "DETAIL_URL" => "",
                "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                "DISPLAY_COMPARE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PREVIEW_TEXT_MODE" => "E",
                "ELEMENT_CODE" => "",
                "ELEMENT_ID" => $arParams["element_id"],
                "FB_USE" => "N",
                "FILE_404" => "",
                "GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
                "GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
                "GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
                "GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
                "GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
                "GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
                "GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
                "GIFTS_MESS_BTN_BUY" => "Выбрать",
                "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
                "GIFTS_SHOW_IMAGE" => "Y",
                "GIFTS_SHOW_NAME" => "Y",
                "GIFTS_SHOW_OLD_PRICE" => "Y",
                "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                "IBLOCK_ID" => IBLOCK_CATALOG_CATALOG,
                "IBLOCK_TYPE" => "catalog",
                "LABEL_PROP" => array("NEWPRODUCT"),
                "LABEL_PROP_MOBILE" => array(),
                "LABEL_PROP_POSITION" => "top-left",
                "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
                "LINK_IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU,
                "LINK_IBLOCK_TYPE" => "catalog",
                "LINK_PROPERTY_SID" => "",
                "MAIN_BLOCK_OFFERS_PROPERTY_CODE" => array("COLOR_REF"),
                "MAIN_BLOCK_PROPERTY_CODE" => array("MATERIAL"),
                "MESSAGE_404" => "",
                "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                "MESS_BTN_BUY" => "Купить",
                "MESS_BTN_COMPARE" => "Сравнение",
                "MESS_BTN_SUBSCRIBE" => "",
                "MESS_COMMENTS_TAB" => "",
                "MESS_DESCRIPTION_TAB" => "",
                "MESS_NOT_AVAILABLE" => "Нет в наличии",
                "MESS_PROPERTIES_TAB" => "Характеристики",
                "MESS_RELATIVE_QUANTITY_FEW" => "мало",
                "MESS_RELATIVE_QUANTITY_MANY" => "много",
                "MESS_SHOW_MAX_QUANTITY" => "Наличие",
                "META_DESCRIPTION" => "-",
                "META_KEYWORDS" => "-",
                "OFFERS_CART_PROPERTIES" => array("ARTNUMBER", "COLOR_REF", "SIZES_SHOES", "SIZES_CLOTHES"),
                "OFFERS_FIELD_CODE" => array(
                    0 => "NAME",
                    1 => "DETAIL_PICTURE",
                    2 => "CODE",
                    3 => "DETAIL_TEXT"
                ),
                "OFFERS_LIMIT" => "0",
                "OFFERS_PROPERTY_CODE" => array("*"),
                "OFFERS_SORT_FIELD" => "sort",
                "PROPERTY_CML2_ARTICLE" => "asc",
                "OFFERS_SORT_ORDER" => "asc",
                "OFFERS_SORT_ORDER2" => "asc",
                "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                "OFFER_TREE_PROPS" => array("COLOR_REF", "SIZES_SHOES", "SIZES_CLOTHES"),
                "PARTIAL_PRODUCT_PROPERTIES" => "Y",
                "PRICE_CODE" => array(PRICE_CODE),
                "PRICE_VAT_INCLUDE" => "Y",
                "PRICE_VAT_SHOW_VALUE" => "N",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
                "PRODUCT_PAY_BLOCK_ORDER" => "rating,price,quantityLimit,quantity,buttons",
                "PRODUCT_PROPERTIES" => array("NEWPRODUCT", "SALELEADER", "MATERIAL"),
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PRODUCT_QUANTITY_VARIABLE" => "",
                "PRODUCT_SUBSCRIPTION" => "Y",
                "PROPERTY_CODE" => array("MANUFACTURER", "MATERIAL", ""),
                "RELATIVE_QUANTITY_FACTOR" => "5",
                "SECTION_CODE" => "",
                "SECTION_CODE_PATH" => "",
                "SECTION_ID" => '',
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "SECTION_URL" => "",
                "SEF_MODE" => "N",
                "SEF_RULE" => "",
                "SET_BROWSER_TITLE" => "N",
                "SET_CANONICAL_URL" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "Y",
                "SET_META_KEYWORDS" => "Y",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SET_VIEWED_IN_COMPONENT" => "N",
                "SHOW_404" => "N",
                "SHOW_CLOSE_POPUP" => "N",
                "SHOW_DEACTIVATED" => "N",
                "SHOW_DISCOUNT_PERCENT" => "Y",
                "SHOW_MAX_QUANTITY" => "M",
                "SHOW_OLD_PRICE" => "N",
                "SHOW_PRICE_COUNT" => "1",
                "SHOW_SLIDER" => "Y",
                "SLIDER_INTERVAL" => "5000",
                "SLIDER_PROGRESS" => "N",
                "STRICT_SECTION_CHECK" => "N",
                "TEMPLATE_THEME" => "blue",
                "USE_COMMENTS" => "Y",
                "USE_ELEMENT_COUNTER" => "Y",
                "USE_ENHANCED_ECOMMERCE" => "Y",
                "USE_GIFTS_DETAIL" => "Y",
                "USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
                "USE_MAIN_ELEMENT_SECTION" => "N",
                "USE_PRICE_COUNT" => "N",
                "USE_PRODUCT_QUANTITY" => "Y",
                "USE_VOTE_RATING" => "Y",
                "VK_USE" => "N",
                "VOTE_DISPLAY_AS_RATING" => "rating",
                "OFFER_ID_SELECTED" => $arParams['offer_id'],
                "IMAGE_SIZE" => [
                    "WIDTH" => 557,
                    "HEIGHT" => 366
                ],
                "DEVICE_TYPE" => DEVICE_TYPE
            )
        );
        $return = ob_get_contents();
        ob_end_clean();

        return ["js_callback" => "getCatalogElementCallBack", "html" => $return];
    }

    /**
     * @param $arParams
     * @return array
     */
    public function setOfferId($arParams)
    {
        $_SESSION['REMEMBER_OFFER'][$arParams['element_code']] = $arParams['offer_id'];

        return [
            "js_callback" => "setOfferIdCallBack",
            "result" => true
        ];
    }
}
