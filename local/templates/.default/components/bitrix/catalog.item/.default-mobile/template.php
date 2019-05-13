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

$arPrice = $arResult["OFFER"]["ITEM_PRICES"][$arResult["OFFER"]["ITEM_PRICE_SELECTED"]];
?>

<?if ($arStatus = \kDevelop\Help\Tools::getOfferStatusInfo($arResult["OFFER"]["PROPERTIES"]["STATUS"]["VALUE_XML_ID"])) :?>
    <div class="label" style="background-color:<?=$arStatus["BG_COLOR"]?>"><?=$arResult["OFFER"]["PROPERTIES"]["STATUS"]["VALUE"]?></div>
<?endif?>
<div<?if (isset($arResult["AREA_ID"])) :?> id="<?=$arResult["AREA_ID"]?>"<?endif?> class="table_list-item-wrap">
    <div class="table_list-wrap">
        <a href="<?=$arResult["OFFER"]["DETAIL_PAGE_URL"]?>" class="table_list-preview full">
            <div class="table_list-img">
                <img src="<?=(is_array($arResult["OFFER"]["PREVIEW_PICTURE"]) ? $arResult["OFFER"]["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>" alt="<?=$arResult["OFFER"]["NAME"]?>">
            </div>
            <div class="table_list-title"><?=$arResult["OFFER"]["NAME"]?></div>
        </a>
        <?if ($arResult["OFFERS_COUNT"] > 0) :
            $wrapAttrs = "";
            $className = "";
            if ($arResult["OFFERS_COUNT"] > 8) {
                $className .= " js-slider";
                $wrapAttrs .= ' 
                    data-autoplay="false"
                    data-autoplaySpeed="5000"
                    data-infinite="true"
                    data-speed="500"
                    data-arrows="true"
                    data-dots="false"
                    data-slidesToShow="8"
                    data-slidesToScroll="1"
                    data-showArrows="true"
                ';
            }
            ?>
            <div class="table_list-color clearfix <?=$className?>"<?=$wrapAttrs?>>
                <?foreach ($arResult["OFFERS_LIST"] as $offerKey => $arOffer) :?>
                    <div>
                        <a
                                href="<?=$arOffer["DETAIL_PAGE_URL"]?>"
                                class="table_list-color-item"
                                title="<?=$arOffer["PROPERTIES"]["Color"]["VALUE"]?>"
                                style="background-color:<?=\kDevelop\Help\Tools::getOfferColor($arOffer["PROPERTIES"]["Color"]["VALUE"])?>"
                        ></a>
                    </div>
                <?endforeach;?>
            </div>
        <?else:?>
            <div class="table_list-color">&nbsp;</div>
        <?endif?>
        <div class="table_list-desc full">
            <?foreach (["Color", "Size", "CML2_MANUFACTURER"] as $code) :
                $value = $arResult["OFFER"]["PROPERTIES"][$code]["VALUE"];
                if (!isset($arResult["OFFER"]["PROPERTIES"][$code]) || strlen($arResult["OFFER"]["PROPERTIES"][$code]["VALUE"]) == 0) {
                    $value = "-";
                }
                ?>
                <div class="table_list-desc-item"><span><?=$arResult["OFFER"]["PROPERTIES"][$code]["NAME"]?>:</span> <?=$value?></div>
            <?endforeach?>
        </div>
        <?if ($arPrice) :?>
            <div class="table_list-info border full">
                <div class="table_list-price_wrap">
                    <?if ($arParams['SHOW_OLD_PRICE'] == "Y") :?>
                        <s><?=$arPrice['PRINT_RATIO_BASE_PRICE']?></s><br>
                    <?endif?>
                    <div class="table_list-price text-line"><?=$arPrice["PRINT_RATIO_PRICE"]?></div>
                    <span>c НДС</span>
                </div>
                <?if ($arResult["OFFER"]["CAN_BUY"]) :?>
                    <a href="#" class="table_list-basket" onclick="obAjax.addToBasket('<?=$arResult["OFFER"]["ID"]?>', '<?=$arPrice["PRICE_TYPE_ID"]?>', event)">
                        <i class="icon basket-white"></i>
                        <span><?=$arParams["MESS_BTN_ADD_TO_BASKET"]?></span>
                    </a>
                <?endif?>
            </div>
        <?endif?>
        <div class="table_list-info full hide_in_list<?if (!$arPrice) :?> margin<?endif?>">
            <?if (isset($arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) && strlen($arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) > 0) :?>
                <div class="table_list-desc-item"><span><?=$arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
            <?endif?>
            <?if (is_array($arResult["OFFER"]["QNT_INFO"])) :?>
                <div class="table_list-status <?=$arResult["OFFER"]["QNT_INFO"]["CLASS"]?>"><?=$arResult["OFFER"]["QNT_INFO"]["MSG_TEXT"]?></div>
            <?endif?>
        </div>
        <div class="show_in_list">
            <div class="table_list-info">
                <a href="#">
                    <i class="icon favorite"></i>
                    <span>Купить в 1 клик</span>
                </a>
            </div>
            <div class="table_list-info">
                <a href="#" onclick="obAjax.addToFavorite('<?=$arResult["ITEM"]["ID"]?>', event)">
                    <i class="icon favorite"></i>
                    <span>В избранное</span>
                </a>
            </div>
            <div class="table_list-info">
                <a href="#">
                    <i class="icon favorite"></i>
                    <span>Сравнить</span>
                </a>
            </div>
        </div>
    </div>
</div>