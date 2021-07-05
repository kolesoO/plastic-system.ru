<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Main\Loader;
use kDevelop\MetaTemplates\CurrentStoreTemplate;
use kDevelop\Service\MultiSite;

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

global $APPLICATION;

$arOffer = $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]];

if (!$arOffer) {
    $arOffer = $arResult;
}
$arPrice = $arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]];
$arOffer["CAN_BUY"] = $arOffer["CAN_BUY"] && $arPrice["PRICE"] > 0;

//seo fields
$rsIProps = new ElementValues($arParams["IBLOCK_ID"], $arResult["ID"]);
$arIPropValues = $rsIProps->getValues();

if ($arIPropValues["ELEMENT_META_TITLE"]) {
    $APPLICATION->SetPageProperty(
        "title",
        CurrentStoreTemplate::calculateForce($arIPropValues["ELEMENT_META_TITLE"])
    );
}
if ($arIPropValues["ELEMENT_META_KEYWORDS"]) {
    $APPLICATION->SetPageProperty(
        "keywords",
        CurrentStoreTemplate::calculateForce($arIPropValues["ELEMENT_META_KEYWORDS"])
    );
}
if ($arIPropValues["ELEMENT_META_DESCRIPTION"]) {
    $APPLICATION->SetPageProperty(
        "description",
        CurrentStoreTemplate::calculateForce($arIPropValues["ELEMENT_META_DESCRIPTION"])
    );
}
if ($arIPropValues["ELEMENT_PAGE_TITLE"]) {
    $APPLICATION->SetTitle(
        CurrentStoreTemplate::calculateForce($arIPropValues["ELEMENT_PAGE_TITLE"])
    );
}
//end

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;

	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

// check compared state
if ($arParams['DISPLAY_COMPARE']) :?>
    <script>obCatalogElementDetail.initCompare(<?=array_key_exists($arOffer['ID'], $_SESSION[$arParams['COMPARE_NAME']][$arParams['IBLOCK_ID']]['ITEMS']) ? "true" : "false"?>);</script>
<?endif;
//end

/** --- SCHEMA ORG + OPEN GRAPH --- **/
if(\Bitrix\Main\Loader::includeModule('impulsit.expansionsite'))
{
    /**
     * @brief Инициализация MetaTag
     * @param Type = Article - Статьи детальная
     * @param Type = ListItem - Статьи список
     * @param Type = Product - Товар детальная
     * @param Type = ProductItem - Товар список
     * @param $arResult = массив данных
     * @param $arParams = массив данных
     * @return Буферизированный контент
     **/
    \Impulsit\ExpansionSite\MetaTag::init('Product',$arResult,$arParams);
}

// check favorite
?>
<script>obCatalogElementDetail.initFavorite(<?=\kDevelop\Ajax\Favorite::isAdded($arResult["ID"]) ? "true" : "false"?>);</script>
<?
//end

//Характеристики, наличие, доставка
$afterTmp = "catalog_element_after";
if ($arParams["DEVICE_TYPE"] == "TABLET") {
    $afterTmp .= "-tablet";
} elseif ($arParams["DEVICE_TYPE"] == "MOBILE") {
    $afterTmp .= "-mobile";
}
$APPLICATION->IncludeComponent(
    "kDevelop:blank",
    $afterTmp,
    array(
        "OFFER" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]],
        "ELEMENT_ID" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["ID"],
        "ELEMENT_PROPERTIES" => $arResult["PROPERTIES"],
        "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
        'USE_MIN_AMOUNT' =>  $arParams['USE_MIN_AMOUNT'],
        'STORE_PATH' => $arParams['STORE_PATH'],
        'MIN_AMOUNT' => $arParams['MIN_AMOUNT'],
        'STORES' => $arParams['STORES'],
        'SHOW_EMPTY_STORE' => $arParams['SHOW_EMPTY_STORE'],
        'SHOW_GENERAL_STORE_INFORMATION' => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
        'USER_FIELDS' => $arParams['USER_FIELDS'],
        'FIELDS' => $arParams['FIELDS'],
        'EXCLUDE_PROPS' => [
            "CML2_BASE_UNIT", "CML2_TAXES", "STATUS", "is_main",
            "CML2_LINK", "DLINA_MM_NUMBER", "SHIRINA_MM_NUMBER", "VYSOTA_MM_NUMBER",
            "BLOG_POST_ID", "BLOG_COMMENTS_CNT", "PRICE_FROM",
        ]
    )
);
//end
?>

</div></div></section>

<?
//Сопутствующие товары
if (is_array($arResult["PROPERTIES"]["MORE_ITEMS"]["VALUE"]) && count($arResult["PROPERTIES"]["MORE_ITEMS"]["VALUE"]) > 0) {
    $GLOBALS["arMoreItemsFilter"] = [
        "XML_ID" => $arResult["PROPERTIES"]["MORE_ITEMS"]["VALUE"],
        "!OFFERS" => false
    ];
    $arCatalogTopParams = [
        "ITEMS_COUNT" => 5,
        "LINE_ELEMENT_COUNT" => 5
    ];
    if ($arParams["DEVICE_TYPE"] == "TABLET") {
        $arCatalogTopParams["LINE_ELEMENT_COUNT"] = 3;
    } elseif ($arParams["DEVICE_TYPE"] == "MOBILE") {
        $arCatalogTopParams["LINE_ELEMENT_COUNT"] = 1;
    }

    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "more-elements",
        Array(
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
            "CACHE_TIME" => "36000000",
            "CACHE_TYPE" => "A",
            "COMPATIBLE_MODE" => "Y",
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => CURRENCY_ID,
            "CUSTOM_FILTER" => "",
            "DATA_LAYER_NAME" => "dataLayer",
            "DETAIL_URL" => "",
            "DISABLE_INIT_JS_IN_COMPONENT" => "N",
            "DISCOUNT_PERCENT_POSITION" => "bottom-right",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_SORT_FIELD" => "rand",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_ORDER2" => "desc",
            "ENLARGE_PRODUCT" => "PROP",
            "ENLARGE_PROP" => "NEWPRODUCT",
            "FILTER_NAME" => "arMoreItemsFilter",
            "HIDE_NOT_AVAILABLE" => "N",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "INCLUDE_SUBSECTIONS" => "Y",
            "LABEL_PROP" => array("NEWPRODUCT"),
            "LABEL_PROP_MOBILE" => array(),
            "LABEL_PROP_POSITION" => "top-left",
            "LAZY_LOAD" => "Y",
            "LINE_ELEMENT_COUNT" => $arCatalogTopParams["LINE_ELEMENT_COUNT"],
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
            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
            "PAGER_TITLE" => "Товары",
            "PAGE_ELEMENT_COUNT" => $arCatalogTopParams["ITEMS_COUNT"],
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRICE_CODE" => $arParams["~PRICE_CODE"],
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
            "PRODUCT_DISPLAY_MODE" => "Y",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_PROPERTIES" => array("NEWPRODUCT","MATERIAL"),
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PRODUCT_QUANTITY_VARIABLE" => "",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
            "PRODUCT_SUBSCRIPTION" => "Y",
            "PROPERTY_CODE" => array("NEWPRODUCT",""),
            "PROPERTY_CODE_MOBILE" => array(),
            "RCM_PROD_ID" => "",
            "RCM_TYPE" => "personal",
            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            "SECTION_ID" => "",
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
            "USE_PRODUCT_QUANTITY" => "N",
            "DISPLAY_COMPARE" => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
            "COMPARE_PATH" => $arResult['URL_TEMPLATES']['compare'],
            "MESS_BTN_COMPARE" => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),
            "USE_COMPARE_LIST" => 'Y',
            "IMAGE_SIZE" => $arParams["ELEMENT_IMAGE_SIZE"],
            "DEVICE_TYPE" => $arParams["DEVICE_TYPE"]
        )
    );
}
//end

//форма "Запрос цены"
?>
<div id="price-order" class="popup">
    <div class="popup_wrapper">
        <div class="popup_content js-popup_content">
            <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
            <?$APPLICATION->IncludeComponent(
                "bitrix:form.result.new",
                "get-price",
                [
                    "SEF_MODE" => "N",
                    "WEB_FORM_ID" => WEB_FORM_GET_PRICE,
                    "LIST_URL" => "",
                    "EDIT_URL" => "",
                    "SUCCESS_URL" => "",
                    "CHAIN_ITEM_TEXT" => "",
                    "CHAIN_ITEM_LINK" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "Y",
                    "USE_EXTENDED_ERRORS" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600",
                    "AJAX_MODE" => "Y",
                    "AJAX_OPTION_SHADOW" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "FIELD_VALUES" => [
                        "product" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["NAME"]
                    ]
                ]
            );?>
        </div>
    </div>
</div>
<?//end

//форма "Сообщить о поступлении"?>
<div id="more-cheap" class="popup">
    <div class="popup_wrapper">
        <div class="popup_content js-popup_content">
            <a href="#" class="popup_content-close" data-popup-close>
                <i class="icon close"></i>
            </a>
            <?$APPLICATION->IncludeComponent(
                "bitrix:form.result.new",
                "",
                [
                    "SEF_MODE" => "N",
                    "WEB_FORM_ID" => WEB_FORM_MORE_CHEAP,
                    "LIST_URL" => "",
                    "EDIT_URL" => "",
                    "SUCCESS_URL" => "",
                    "CHAIN_ITEM_TEXT" => "",
                    "CHAIN_ITEM_LINK" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "Y",
                    "USE_EXTENDED_ERRORS" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600",
                    "AJAX_MODE" => "Y",
                    "AJAX_OPTION_SHADOW" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "FIELD_VALUES" => [
                        "product" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["NAME"]
                    ]
                ]
            );?>
        </div>
    </div>
</div>
<?//end

//форма "Купить в 1 клик"?>
<div id="buy-one-click" class="popup">
    <div class="popup_wrapper">
        <div class="popup_content js-popup_content">
            <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
            <?$APPLICATION->IncludeComponent(
                "bitrix:form.result.new",
                "buy-one-click",
                [
                    "SEF_MODE" => "N",
                    "WEB_FORM_ID" => WEB_FORM_BUY_ONE_CLICK,
                    "LIST_URL" => "",
                    "EDIT_URL" => "",
                    "SUCCESS_URL" => "",
                    "CHAIN_ITEM_TEXT" => "",
                    "CHAIN_ITEM_LINK" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "Y",
                    "USE_EXTENDED_ERRORS" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600",
                    "AJAX_MODE" => "Y",
                    "AJAX_OPTION_SHADOW" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "FIELD_VALUES" => [
                        "product" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["NAME"]
                    ]
                ]
            );?>
        </div>
    </div>
</div>
<?//end?>

<?
//форма под заказ
if (!$arOffer["CAN_BUY"]) :?>
    <div id="pre-order" class="popup">
        <div class="popup_wrapper">
            <div class="popup_content js-popup_content">
                <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:form.result.new",
                    "",
                    [
                        "SEF_MODE" => "N",
                        "WEB_FORM_ID" => WEB_FORM_PRE_ORDER,
                        "LIST_URL" => "",
                        "EDIT_URL" => "",
                        "SUCCESS_URL" => "",
                        "CHAIN_ITEM_TEXT" => "",
                        "CHAIN_ITEM_LINK" => "",
                        "IGNORE_CUSTOM_TEMPLATE" => "Y",
                        "USE_EXTENDED_ERRORS" => "Y",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "AJAX_MODE" => "Y",
                        "AJAX_OPTION_SHADOW" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "FIELD_VALUES" => [
                            "product" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["NAME"]
                        ]
                    ]
                );?>
            </div>
        </div>
    </div>
<?endif;
//end

//форма цена "от"
if ($arOffer['PROPERTIES']['PRICE_FROM']['VALUE'] === 'Y') :?>
    <div id="price_from-form" class="popup">
        <div class="popup_wrapper">
            <div class="popup_content js-popup_content">
                <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:form.result.new",
                    "",
                    [
                        "SEF_MODE" => "N",
                        "WEB_FORM_ID" => WEB_FORM_PRICE_FROM,
                        "LIST_URL" => "",
                        "EDIT_URL" => "",
                        "SUCCESS_URL" => "",
                        "CHAIN_ITEM_TEXT" => "",
                        "CHAIN_ITEM_LINK" => "",
                        "IGNORE_CUSTOM_TEMPLATE" => "Y",
                        "USE_EXTENDED_ERRORS" => "Y",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "AJAX_MODE" => "Y",
                        "AJAX_OPTION_SHADOW" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "FIELD_VALUES" => [
                            "product" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["NAME"]
                        ]
                    ]
                );?>
            </div>
        </div>
    </div>
<?endif;
//end
