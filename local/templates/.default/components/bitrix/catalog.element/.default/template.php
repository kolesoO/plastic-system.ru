<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$offerSlidesToShow = 8;
$arOffer = $arResult["OFFERS"][$arResult["OFFER_ID_SELECTED"]];
if (!$arOffer) {
    $arOffer = $arResult;
}
$arPrice = $arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]];
$arOffer["CAN_BUY"] = /*$arOffer["CAN_BUY"] && */$arPrice["PRICE"] > 0;

//параметры для js
$jsParams = [
    "OFFER_ID" => $arOffer["ID"]
];
if ($arParams['DISPLAY_COMPARE']) {
    $jsParams['compare'] = array(
        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'COMPARE_PATH' => $arParams['COMPARE_PATH']
    );
}
///end
?>

<div class="block_wrapper">
    <?if ($arStatus = \kDevelop\Help\Tools::getOfferStatusInfo($arOffer["PROPERTIES"]["STATUS"]["VALUE_XML_ID"])) :?>
        <div class="label absolute" style="background-color:<?=$arStatus["BG_COLOR"]?>"><?=$arOffer["PROPERTIES"]["STATUS"]["VALUE"]?></div>
    <?endif?>
    <div class="cart">
        <div class="cart_img">
            <?if (is_array($arOffer["DETAIL_PICTURE"]) || is_array($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"])) :?>
                <div
                        class="js-slider"
                        data-autoplay="true"
                        data-autoplaySpeed="5000"
                        data-infinite="true"
                        data-speed="500"
                        data-arrows="false"
                        data-dots="true"
                        data-slidesToShow="1"
                        data-slidesToScroll="1"
                        data-showArrows="true"
                >
                    <?if (is_array($arOffer["DETAIL_PICTURE"])) :?>
                        <div><img src="<?=$arOffer["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arOffer["NAME"]?>"></div>
                    <?endif?>
                    <?if (is_array($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"])) :?>
                        <?foreach ($arOffer["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $filePath) :?>
                            <div><img src="<?=$filePath?>" alt="<?=$arOffer["NAME"]?>"></div>
                        <?endforeach?>
                    <?endif?>
                </div>
            <?else:?>
                <div align="center"><img src="<?=SITE_TEMPLATE_PATH?>/images/no-image.png"></div>
            <?endif?>
        </div>
        <div class="cart_desc">
            <?if ((isset($arOffer["PROPERTIES"]["CML2_ARTICLE"]) && strlen($arOffer["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) > 0) || $arPrice) :?>
                <div class="cart_desc-item">
                    <?if (isset($arOffer["PROPERTIES"]["CML2_ARTICLE"]) && strlen($arOffer["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) > 0) :?>
                        <div class="table_list-desc-item"><span><?=$arOffer["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arOffer["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
                    <?endif?>
                    <?if ($arPrice["PRICE"] > 0) :?>
                        <br>
                        <div class="table_list-price_wrap">
                            <?if ($arParams['SHOW_OLD_PRICE'] == "Y" && $arPrice["BASE_PRICE"] > $arPrice["PRICE"]) :?>
                                <s class="table_list-price_small"><?=$arPrice['PRINT_RATIO_BASE_PRICE']?></s><br>
                            <?endif?>
                            <div class="table_list-price text-line"><?=$arPrice["PRINT_PRICE"]?></div>
                            <span>c НДС</span>
                        </div>
                        <br>
                        <span><a href="#" class="link dashed" data-popup-open="#price-order">Запросить</a> оптовую цену</span>
                    <?endif?>
                </div>
            <?endif?>
            <?if ($arResult["TSVET"]["COUNT"] > 0) :
                $wrapClass = "table_list-color clearfix";
                $wrapData = "";
                $isSlider = false;
                if ($arResult["TSVET"]["COUNT"] > $offerSlidesToShow) {
                    $wrapClass .= " js-slider";
                    $wrapData .= ' 
                            data-autoplay="false"
                            data-autoplaySpeed="5000"
                            data-infinite="true"
                            data-speed="500"
                            data-arrows="'.($arParams["DEVICE_TYPE"] == "MOBILE" ? "true" : "false").'"
                            data-dots="false"
                            data-slidesToShow="'.$offerSlidesToShow.'"
                            data-slidesToScroll="1"
                            data-showArrows="true"
                        ';
                    $isSlider = true;
                }
                ?>
                <div class="cart_desc-item">
                    <div class="<?=$wrapClass?>"<?=$wrapData?>>
                        <?foreach ($arResult["OFFERS"] as $offerKey => $arOfferItem) :
                            if (!in_array($arOfferItem["ID"], $arResult["TSVET"]["ID"])) continue;
                            ?>
                            <?if ($isSlider) :?><div><?endif?>
                            <?if ($arResult["OFFER_ID_SELECTED"] == $offerKey) :?>
                                <div
                                        class="table_list-color-item slick-current"
                                        title="<?=$arOfferItem["PROPERTIES"]["TSVET"]["VALUE"]?>"
                                        style="background-color:<?=\kDevelop\Help\Tools::getOfferColor($arOfferItem["PROPERTIES"]["TSVET"]["VALUE"])?>"
                                ></div>
                            <?else:?>
                                <a
                                        href="<?=$arOfferItem["DETAIL_PAGE_URL"]?>"
                                        class="table_list-color-item"
                                        title="<?=$arOfferItem["PROPERTIES"]["TSVET"]["VALUE"]?>"
                                        style="background-color:<?=\kDevelop\Help\Tools::getOfferColor($arOfferItem["PROPERTIES"]["TSVET"]["VALUE"])?>"
                                ></a>
                            <?endif?>
                            <?if ($isSlider) :?></div><?endif?>
                        <?endforeach?>
                    </div>
                </div>
            <?endif?>
            <?foreach (["RAZMER", "OPTSII_IBOX", "DNO", "TIP_KONTEYNERA"] as $code) :?>
                <?if ($arResult[$code]["COUNT"] > 0) :?>
                    <div class="cart_desc-item">
                        <div class="dropdown header-location js-drop_down">
                            <a href="#" class="dropdown-btn link js-drop_down-btn">
                                <span><?=$arResult[$code]["TITLE"]?></span>
                                <?if (strlen($arOffer["PROPERTIES"][$code]["VALUE"]) > 0) :
                                    $prefix = strlen($arOffer["PROPERTIES"]["TSVET"]["VALUE"]) > 0 ? $arOffer["PROPERTIES"]["TSVET"]["VALUE"].", " : "";
                                    ?>
                                    <small>(<?=$prefix.$arOffer["PROPERTIES"][$code]["VALUE"]?>)</small>
                                <?endif?>
                            </a>
                            <div class="header-location-content js-drop_down-content">
                                <div class="header-location-inner">
                                    <?foreach ($arResult["OFFERS"] as $offerKey => $arOfferItem) :
                                        if (!in_array($arOfferItem["ID"], $arResult[$code]["ID"])) continue;
                                        $prefix = strlen($arOfferItem["PROPERTIES"]["TSVET"]["VALUE"]) > 0 ? $arOfferItem["PROPERTIES"]["TSVET"]["VALUE"].", " : "";
                                        ?>
                                        <?if ($offerKey == $arResult["OFFER_ID_SELECTED"]) :?>
                                            <span class="header-location-link"><?=$prefix.$arOfferItem["PROPERTIES"][$code]["VALUE"]?></span>
                                        <?else:?>
                                            <a href="<?=$arOfferItem["DETAIL_PAGE_URL"]?>" class="header-location-link link"><?=$prefix.$arOfferItem["PROPERTIES"][$code]["VALUE"]?></a>
                                        <?endif?>
                                    <?endforeach?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endif?>
            <?endforeach?>
            <div class="cart_desc-item">
                <div class="cart_buy">
                    <?if ($arOffer["CAN_BUY"]) :?>
                        <div class="cart_buy-qnt_wrap">
                            <a
                                    href="#"
                                    class="cart_buy-qnt"
                                    data-target="#qnt-to-basket"
                                    onclick="obCatalogElementDetail.setQnt(this, 'plus')"
                            >+</a>
                            <input
                                    id="qnt-to-basket"
                                    type="text"
                                    class="cart_buy-qnt"
                                    value="1"
                                    onchange="obCatalogElementDetail.changeQnt(this)"
                            >
                            <a
                                    href="#"
                                    class="cart_buy-qnt"
                                    data-target="#qnt-to-basket"
                                    onclick="obCatalogElementDetail.setQnt(this, 'minus')"
                            >-</a>
                        </div>
                        <a
                                href="#"
                                class="table_list-basket"
                                data-qnt-target="#qnt-to-basket"
                                onclick="obAjax.addToBasket('<?=$arOffer["ID"]?>', '<?=$arPrice["PRICE_TYPE_ID"]?>', event)"
                        >
                            <i class="icon basket-white"></i>
                            <span><?=$arParams["MESS_BTN_ADD_TO_BASKET"]?></span>
                        </a>
                    <?else: ?>
                        <a href="#" class="table_list-basket col-xs-24" data-popup-open="#pre-order">Под заказ</a>
                    <?endif?>
                </div>
                <div class="cart_links">
                    <?if ($arOffer["CAN_BUY"]) :?>
                        <a href="#" class="cart_links-item" data-popup-open="#buy-one-click">
                            <i class="icon buy_one_click"></i>
                            <span>Купить в 1 клик</span>
                        </a>
                    <?endif?>
                    <a href="#" class="cart_links-item" data-entity="favorite" data-id="<?=$arResult["ID"]?>">
                        <i class="icon favorite"></i>
                        <span><?=GetMessage("CT_BCE_CATALOG_MESS_FAVORITE_TITLE")?></span>
                    </a>
                    <?if ($arParams['DISPLAY_COMPARE']) :?>
                        <a href="#" class="cart_links-item" data-entity="compare" data-id="<?=$arResult["ID"]?>">
                            <i class="icon compare"></i>
                            <span><?=GetMessage("CT_BCE_CATALOG_MESS_COMPARE_TITLE")?></span>
                        </a>
                    <?endif?>
                    <!--a href="#" class="cart_links-item">
                        <i class="icon favorite"></i>
                        <span>Распечатать</span>
                    </a-->
                </div>
            </div>
        </div>
    </div>
</div>
<?if (strlen($arOffer["DETAIL_TEXT"]) > 0) :?>
    <div class="block_wrapper"><?=($arOffer["DETAIL_TEXT_TYPE"] == "text" ? $arOffer["DETAIL_TEXT"] : htmlspecialcharsback($arOffer["DETAIL_TEXT"]))?></div>
<?elseif (strlen($arResult["DETAIL_TEXT"]) > 0): ?>
    <div class="block_wrapper"><?=($arResult["DETAIL_TEXT_TYPE"] == "text" ? $arResult["DETAIL_TEXT"] : htmlspecialcharsback($arResult["DETAIL_TEXT"]))?></div>
<?endif?>
<script>
    BX.message({
        ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
        TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
        TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
        BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
        BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
        BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
        BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
        BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
        TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
        COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
        COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
        COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
        BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
        PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
        PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
        RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
        RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
        SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>',
        BTN_MESSAGE_FAVORITE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_FAVORITE_REDIRECT')?>',
        FAVORITE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_FAVORITE_TITLE')?>'
    });
    var obCatalogElementDetail = new window.catalogElementDetail(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>