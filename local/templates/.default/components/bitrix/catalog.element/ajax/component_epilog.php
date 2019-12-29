<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

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
            "BLOG_POST_ID", "BLOG_COMMENTS_CNT"
        ]
    )
);
//end
?>
