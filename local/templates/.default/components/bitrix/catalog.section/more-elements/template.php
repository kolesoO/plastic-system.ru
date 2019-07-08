<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$arCatalogItemsParams = [
    "PARAMS" => $arResult["ORIGINAL_PARAMETERS"],
    "CATALOGS" => $arResult["CATALOGS"]
];
?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
    <section class="section">
        <div class="container">
            <div class="title-2">Сопутствующие товары</div>
            <div
                    class="table_list catalog clearfix<?if ($arResult["IS_SLIDER"]) :?> js-slider js-main_slider<?endif?>"
                    <?if ($arResult["IS_SLIDER"]) :?>
                        data-autoplay="true"
                        data-autoplaySpeed="5000"
                        data-infinite="true"
                        data-speed="1000"
                        data-arrows="false"
                        data-dots="false"
                        data-slidesToShow="<?=$arParams["LINE_ELEMENT_COUNT"]?>"
                        data-slidesToScroll="1"
                    <?endif?>
                    items-count="<?=$arParams["LINE_ELEMENT_COUNT"]?>"
            >
                <?foreach ($arResult["ITEMS"] as $arItem) :
                    if ($arResult["SET_AREA"]) {
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    }
                    ?>
                    <div id="catalog-item-<?=$arItem["ID"]?>" class="table_list-item">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.item",
                            $arResult["INNER_TEMPLATE"],
                            [
                                "RESULT" => [
                                    "ITEM" => $arItem,
                                    "OFFER_KEY" => 0,
                                    "OFFERS_LIST" => $arItem["OFFERS"],
                                    "WRAP_ID" => "catalog-item-".$arItem["ID"],
                                    "AREA_ID" => $this->GetEditAreaId($arItem["ID"])
                                ],
                                "PARAMS" => array_merge($arResult["ORIGINAL_PARAMETERS"], [
                                    "PRICES" => $arResult["PRICES"],
                                    "COMPARE" => [
                                        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                                        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
                                        'COMPARE_PATH' => $arParams['COMPARE_PATH']
                                    ],
                                    "COMPARE_NAME" => $arParams['COMPARE_NAME'],
                                ])
                            ],
                            $component,
                            ['HIDE_ICONS' => 'Y']
                        );?>
                    </div>
                <?endforeach?>
            </div>
            <?if (isset($arParams["ALL_LINK"]) && strlen($arParams["ALL_LINK"]) > 0) :?>
                <br>
                <a href="<?=$arParams["ALL_LINK"]?>" class="link">
                    <span>Все акции</span><i class="icon arrow-right"></i>
                </a>
            <?endif?>
        </div>
    </section>
    <script>
        BX.message({
            BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BASKET_URL: '<?=$arParams['BASKET_URL']?>',
            ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCS_CATALOG_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            BTN_MESSAGE_LAZY_LOAD: '<?=CUtil::JSEscape($arParams['MESS_BTN_LAZY_LOAD'])?>',
            BTN_MESSAGE_LAZY_LOAD_WAITER: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_LAZY_LOAD_WAITER')?>',
            BTN_MESSAGE_FAVORITE_REDIRECT: '<?=GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_FAVORITE_REDIRECT')?>',
            FAVORITE_TITLE: '<?=GetMessageJS('CT_BCS_CATALOG_MESS_FAVORITE_TITLE')?>',
            SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
        });
        var obCatalogItemsParams = <?=CUtil::PhpToJSObject($arCatalogItemsParams)?>;
    </script>
<?endif?>