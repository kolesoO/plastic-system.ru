<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//seo fields
$rsIProps = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams["LINK_IBLOCK_ID"],$arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["ID"]);
$arIPropValues = $rsIProps->getValues();
if ($arIPropValues["ELEMENT_META_TITLE"]) {
    $APPLICATION->SetPageProperty("title", $arIPropValues["ELEMENT_META_TITLE"]);
}
if ($arIPropValues["ELEMENT_META_KEYWORDS"]) {
    $APPLICATION->SetPageProperty("keywords", $arIPropValues["ELEMENT_META_KEYWORDS"]);
}
if ($arIPropValues["ELEMENT_META_DESCRIPTION"]) {
    $APPLICATION->SetPageProperty("description", $arIPropValues["ELEMENT_META_DESCRIPTION"]);
}
if ($arIPropValues["ELEMENT_PAGE_TITLE"]) {
    $APPLICATION->SetTitle($arIPropValues["ELEMENT_PAGE_TITLE"]);
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

if (isset($templateData['JS_OBJ']))
{
	?>
	<script>
		BX.ready(BX.defer(function(){
			if (!!window.<?=$templateData['JS_OBJ']?>)
			{
				window.<?=$templateData['JS_OBJ']?>.allowViewedCount(true);
			}
		}));
	</script>

	<?
	// check compared state
	if ($arParams['DISPLAY_COMPARE'])
	{
		$compared = false;
		$comparedIds = array();
		$item = $templateData['ITEM'];

		if (!empty($_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]))
		{
			if (!empty($item['JS_OFFERS']))
			{
				foreach ($item['JS_OFFERS'] as $key => $offer)
				{
					if (array_key_exists($offer['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
					{
						if ($key == $item['OFFERS_SELECTED'])
						{
							$compared = true;
						}

						$comparedIds[] = $offer['ID'];
					}
				}
			}
			elseif (array_key_exists($item['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
			{
				$compared = true;
			}
		}

		if ($templateData['JS_OBJ'])
		{
			?>
			<script>
				BX.ready(BX.defer(function(){
					if (!!window.<?=$templateData['JS_OBJ']?>)
					{
						window.<?=$templateData['JS_OBJ']?>.setCompared('<?=$compared?>');

						<? if (!empty($comparedIds)): ?>
						window.<?=$templateData['JS_OBJ']?>.setCompareInfo(<?=CUtil::PhpToJSObject($comparedIds, false, true)?>);
						<? endif ?>
					}
				}));
			</script>
			<?
		}
	}

	// select target offer
	$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$offerNum = false;
	$offerId = (int)$this->request->get('OFFER_ID');
	$offerCode = $this->request->get('OFFER_CODE');

	if ($offerId > 0 && !empty($templateData['OFFER_IDS']) && is_array($templateData['OFFER_IDS']))
	{
		$offerNum = array_search($offerId, $templateData['OFFER_IDS']);
	}
	elseif (!empty($offerCode) && !empty($templateData['OFFER_CODES']) && is_array($templateData['OFFER_CODES']))
	{
		$offerNum = array_search($offerCode, $templateData['OFFER_CODES']);
	}

	if (!empty($offerNum))
	{
		?>
		<script>
			BX.ready(function(){
				if (!!window.<?=$templateData['JS_OBJ']?>)
				{
					window.<?=$templateData['JS_OBJ']?>.setOffer(<?=$offerNum?>);
				}
			});
		</script>
		<?
	}
}

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
        "OFFER" => $arResult["OFFERS"][$arResult["OFFER_KEY"]],
        "ELEMENT_ID" => $arResult["ID"],
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
    )
);
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

//форма "Купить в 1 клик"
?>
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
                        "product_id" => $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]]["ID"]
                    ]
                ]
            );?>
        </div>
    </div>
</div>
<?//end?>
