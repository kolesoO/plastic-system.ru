<?
namespace kDevelop\Ajax;

class Catalog
{
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
                array_unique(array_merge(self::$arDefIbFields, $arFieldCode))
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
     * @param $arParams
     * @return array
     */
    public static function getCatalogItem($arParams)
    {
        global $APPLICATION;

        $return = "";
        if (\Bitrix\Main\Loader::includeModule("catalog")) {
            if ($arProdList = \CCatalogSKU::getProductList($arParams["offer_id"], $arParams["CATALOGS"][$arParams["PARAMS"]["IBLOCK_ID"]]["IBLOCK_ID"])) {
                $productId = $arProdList[$arParams["offer_id"]]["ID"];
                if ($arItem = self::getIblockElement(
                    [],
                    ["IBLOCK_ID" => $arProdList[$arParams["offer_id"]]["IBLOCK_ID"], "ID" => $productId],
                    ["*"],
                    $arParams["PARAMS"]["PROPERTY_CODE"]
                )) {
                    //данные по ценам
                    if (is_array($arParams["PARAMS"]["PRICE_CODE"]) && count($arParams["PARAMS"]["PRICE_CODE"]) > 0) {
                        $arItem["PRICES"] = \CIBlockPriceTools::GetCatalogPrices(false, $arParams["PARAMS"]["PRICE_CODE"]);
                    }
                    //end
                    if ($arItem["OFFERS"] = self::getIblockElement(
                        [$arParams["PARAMS"]["OFFERS_SORT_FIELD"] => $arParams["PARAMS"]["OFFERS_SORT_ORDER"]],
                        ["IBLOCK_ID" => $arProdList[$arParams["offer_id"]]["OFFER_IBLOCK_ID"], "PROPERTY_".$arProdList[$arParams["offer_id"]]["SKU_PROPERTY_ID"] => $productId],
                        $arParams["PARAMS"]["OFFERS_FIELD_CODE"],
                        $arParams["PARAMS"]["OFFERS_PROPERTY_CODE"]
                    )) {
                        foreach ($arItem["OFFERS"] as $key => &$arOffer) {
                            if ($arOffer["ID"] == $arParams["offer_id"]) {
                                $arItem["OFFER_KEY"] = $key;
                            }
                            $arOffer["PRICES"] = \CIBlockPriceTools::GetItemPrices(
                                $arOffer["IBLOCK_ID"],
                                $arItem["PRICES"],
                                $arOffer,
                                $arParams["PARAMS"]['PRICE_VAT_INCLUDE'],
                                self::getCurrencyConvert($arParams["PARAMS"]["CONVERT_CURRENCY"], $arParams["PARAMS"]["CURRENCY_ID"])
                            );
                        }
                        unset($arOffer);
                        ob_start();
                        $APPLICATION->IncludeComponent(
                            "bitrix:catalog.item",
                            ".default",
                            [
                                "RESULT" => [
                                    "ITEM" => $arItem,
                                    "OFFER_KEY" => $arItem["OFFER_KEY"],
                                    "OFFERS_LIST" => $arItem["OFFERS"],
                                    "WRAP_ID" => $arParams["target_id"]
                                ],
                                "PARAMS" => $arParams["PARAMS"],
                                "PRICES" => $arItem["PRICES"]
                            ],
                            null,
                            ['HIDE_ICONS' => 'Y']
                        );
                        $return = ob_get_contents();
                        ob_end_clean();
                    }
                }
            }
        }

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
}
