<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
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

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'showMore' || $request->get('action') === 'deferredLoad'))
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer
	));
}
?>

<?if ($_REQUEST["bxajaxid"]) :?>
    <script>obSlider.init("#catalog-list");</script>
<?endif?>

<?//форма "Купить в 1 клик"?>
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
                    "AJAX_OPTION_HISTORY" => "N"
                ]
            );?>
        </div>
    </div>
</div>
<?//end?>

<?/** --- SCHEMA ORG + OPEN GRAPH --- **/
if(\Bitrix\Main\Loader::includeModule('impulsit.expansionsite'))
{
    /**
     * @brief Инициализация MetaTag
     * @param Type = Article - Статьи детальная
     * @param Type = ListItem - Статьи список
     * @param Type = Product - Товар детальная
     * @param Type = ProductOffer - Offer список
     * @param Type = ProductItem - Товар список
     * @param $arResult = массив данных
     * @param $arParams = массив данных
     * @return Буферизированный контент
     **/
    \Impulsit\ExpansionSite\MetaTag::init('ProductOffer',$arResult,$arParams);
    \Impulsit\ExpansionSite\MetaTag::init('Pagenavigation',$arResult);
}?>