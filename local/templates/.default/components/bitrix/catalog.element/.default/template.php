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
$arPrice = $arOffer["ITEM_PRICES"][$arOffer["ITEM_PRICE_SELECTED"]];
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
            <div class="cart_desc-item">
                <?if (isset($arOffer["PROPERTIES"]["CML2_ARTICLE"]) && strlen($arOffer["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) > 0) :?>
                    <div class="table_list-desc-item"><span><?=$arOffer["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arOffer["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
                    <br>
                <?endif?>
                <div class="table_list-price_wrap">
                    <?if ($arParams['SHOW_OLD_PRICE'] == "Y") :?>
                        <s class="table_list-price_small"><?=$arPrice['PRINT_RATIO_BASE_PRICE']?></s><br>
                    <?endif?>
                    <div class="table_list-price text-line"><?=$arPrice["PRINT_PRICE"]?></div>
                    <span>c НДС</span>
                </div>
                <br>
                <span><a href="#" class="link dashed" data-popup-open="#price-order">Запросить</a> оптовую цену</span>
            </div>
            <div class="cart_desc-item">
                <?if ($arResult["OFFERS_COUNT"] > 0) :
                    $wrapClass = "table_list-color clearfix";
                    $wrapData = "";
                    $isSlider = false;
                    if ($arResult["OFFERS_COUNT"] > $offerSlidesToShow) {
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
                    <div class="<?=$wrapClass?>"<?=$wrapData?>>
                        <?foreach ($arResult["OFFERS"] as $offerKey => $arOffer) :?>
                            <?if ($isSlider) :?><div><?endif?>
                                <?if ($arResult["OFFER_ID_SELECTED"] == $offerKey) :?>
                                    <div
                                            class="table_list-color-item slick-current"
                                            title="<?=$arOffer["PROPERTIES"]["Color"]["VALUE"]?>"
                                            style="background-color:<?=\kDevelop\Help\Tools::getOfferColor($arOffer["PROPERTIES"]["Color"]["VALUE"])?>"
                                    ></div>
                                <?else:?>
                                    <a
                                        href="<?=$arOffer["DETAIL_PAGE_URL"]?>"
                                        class="table_list-color-item"
                                        title="<?=$arOffer["PROPERTIES"]["Color"]["VALUE"]?>"
                                        style="background-color:<?=\kDevelop\Help\Tools::getOfferColor($arOffer["PROPERTIES"]["Color"]["VALUE"])?>"
                                    ></a>
                                <?endif?>
                            <?if ($isSlider) :?></div><?endif?>
                        <?endforeach?>
                    </div>
                <?endif?>
            </div>
            <div class="cart_desc-item">
                <?if ($arOffer["CAN_BUY"]) :?>
                    <div class="cart_buy">
                        <div class="cart_buy-qnt_wrap">
                            <a href="#" class="cart_buy-qnt">+</a>
                            <input type="text" class="cart_buy-qnt" value="1">
                            <a href="#" class="cart_buy-qnt">-</a>
                        </div>
                        <a href="#" class="table_list-basket" onclick="obAjax.addToBasket('<?=$arOffer["ID"]?>', '<?=$arPrice["PRICE_TYPE_ID"]?>', event)">
                            <i class="icon basket-white"></i>
                            <span><?=$arParams["MESS_BTN_ADD_TO_BASKET"]?></span>
                        </a>
                    </div>
                <?endif?>
                <div class="cart_links">
                    <a href="#" class="cart_links-item" data-popup-open="#buy-one-click">
                        <i class="icon buy_one_click"></i>
                        <span>Купить в 1 клик</span>
                    </a>
                    <a href="#" class="cart_links-item">
                        <i class="icon favorite"></i>
                        <span>В избранное</span>
                    </a>
                    <a href="#" class="cart_links-item">
                        <i class="icon compare"></i>
                        <span>Сравнить</span>
                    </a>
                    <!--a href="#" class="cart_links-item">
                        <i class="icon favorite"></i>
                        <span>Распечатать</span>
                    </a-->
                </div>
            </div>
        </div>
    </div>
</div>