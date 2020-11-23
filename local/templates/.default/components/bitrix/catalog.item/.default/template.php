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
$arResult["OFFER"]["CAN_BUY"] = $arPrice["PRICE"] > 0
    && $arResult["OFFER"]['PROPERTIES']['PRICE_FROM']['VALUE'] !== 'Y';

//параметры для js
$jsParams = [
    "OFFER_ID" => $arResult["OFFER"]["ID"],
    "ITEM_ID" => $arResult["ITEM"]["ID"]
];
if ($arParams['DISPLAY_COMPARE']) {
    $jsParams['compare'] = $arParams["COMPARE"];
}
//end
?>

<?if ($arParams["FAVORITE_ITEM"] == "Y") :?>
    <a
            href="javascript:void(0)"
            class="close_wrap"
            title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>"
            onclick="obAjax.deleteFromFavorite('<?=$arResult["ITEM"]["ID"]?>', 'catalog-item-<?=$arResult["ITEM"]["ID"]?>', event);"
    >
        <i class="icon close"></i>
    </a>
<?endif?>
<?if ($arStatus = \kDevelop\Help\Tools::getOfferStatusInfo($arResult["OFFER"]["PROPERTIES"]["STATUS"]["VALUE_XML_ID"])) :?>
    <div class="label" style="background-color:<?=$arStatus["BG_COLOR"]?>"><?=$arResult["OFFER"]["PROPERTIES"]["STATUS"]["VALUE"]?></div>
<?endif?>
<div<?if (isset($arResult["AREA_ID"])) :?> id="<?=$arResult["AREA_ID"]?>"<?endif?> class="table_list-item-wrap">
    <div class="table_list-wrap">
        <a
                href="<?=$arResult["ITEM"]["DETAIL_PAGE_URL"]?>"
                class="table_list-img"
                style="background-image:url('<?=(is_array($arResult["OFFER"]["PREVIEW_PICTURE"]) ? $arResult["OFFER"]["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>')"
                onclick="obAjax.setOfferId('<?=$arResult["ITEM"]['CODE']?>', '<?=$arResult["OFFER"]['ID']?>', this, event)"
                data-href="<?=$arResult["ITEM"]["DETAIL_PAGE_URL"]?>"
        >
        </a>
        <?if ($arResult["OFFERS_COUNT"] > 0) :
            $wrapAttrs = "";
            $className = "";
            if ($arResult["OFFERS_COUNT"] > 7) {
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
            <div class="table_list-color clearfix<?=$className?>"<?=$wrapAttrs?>>
                <?
                $arColorCache = [];
                foreach ($arResult["OFFERS_LIST"] as $offerKey => $arOffer) :
                    if (strlen($arOffer["PROPERTIES"]["TSVET"]["VALUE"]) == 0 || in_array($arOffer["PROPERTIES"]["TSVET"]["VALUE"], $arColorCache)) continue;
                    $arColorCache[] = $arOffer["PROPERTIES"]["TSVET"]["VALUE"];
                    ?>
                    <?if ($arOffer['ID'] == $arResult["OFFER"]['ID']) :?>
                        <a
                                href="javascript:void(0)"
                                class="table_list-color-item<?if ($arOffer['ID'] == $arResult["OFFER"]['ID']) :?> slick-current<?endif?>"
                                title="<?=$arOffer["PROPERTIES"]["TSVET"]["VALUE"]?>"
                                style="background-color:<?=\kDevelop\Help\Tools::getOfferColor($arOffer["PROPERTIES"]["TSVET"]["VALUE"])?>"
                        ></a>
                    <?else:?>
                        <a
                                href="javascript:void(0)"
                                class="table_list-color-item<?if ($arOffer['ID'] == $arResult["OFFER"]['ID']) :?> slick-current<?endif?>"
                                title="<?=$arOffer["PROPERTIES"]["TSVET"]["VALUE"]?>"
                                style="background-color:<?=\kDevelop\Help\Tools::getOfferColor($arOffer["PROPERTIES"]["TSVET"]["VALUE"])?>"
                                <?if ($arResult['CATALOG_TOP'] == 'Y') :?>
                                    onclick="obAjax.getCatalogItemFromTop('<?=$arResult['WRAP_ID']?>', '<?=$arResult["ITEM"]['ID']?>', '<?=$offerKey?>')"
                                <?else:?>
                                    onclick="obAjax.getCatalogItem('<?=$arResult['WRAP_ID']?>', '<?=$arResult["ITEM"]['ID']?>', '<?=$arOffer['ID']?>')"
                                <?endif?>
                        ></a>
                    <?endif?>
                <?endforeach;?>
            </div>
        <?else:?>
            <div class="table_list-color">&nbsp;</div>
        <?endif?>
    </div>
    <div class="table_list-wrap">
        <a
            href="<?=$arResult["ITEM"]["DETAIL_PAGE_URL"]?>"
            class="table_list-title border full"
            onclick="obAjax.setOfferId('<?=$arResult["ITEM"]['CODE']?>', '<?=$arResult["OFFER"]['ID']?>', this, event)"
            data-href="<?=$arResult["ITEM"]["DETAIL_PAGE_URL"]?>"
        ><?=$arResult["OFFER"]["NAME"]?></a>
        <div class="table_list-info show_in_list">
            <?if (is_array($arResult["OFFER"]["QNT_INFO"])) :?>
                <div class="table_list-status <?=$arResult["OFFER"]["QNT_INFO"]["CLASS"]?>"><?=$arResult["OFFER"]["QNT_INFO"]["MSG_TEXT"]?></div>
            <?endif?>
            <div class="table_list-desc-item"><span><?=$arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
        </div>
        <div class="table_list-desc full">
            <?if (
                    isset($arResult["ITEM"]["PROPERTIES"]["PLOTNOST_KG_M2"])
                    && strlen($arResult["ITEM"]["PROPERTIES"]["PLOTNOST_KG_M2"]["VALUE"]) > 0
            ) :?>
                <div class="table_list-desc-item"><span><?=$arResult["ITEM"]["PROPERTIES"]["PLOTNOST_KG_M2"]["NAME"]?>:</span> <?=$arResult["ITEM"]["PROPERTIES"]["PLOTNOST_KG_M2"]["VALUE"]?></div>
            <?endif?>
            <?
            $propValue = "";
            $propTitle = "";
            foreach (["DLINA_MM", "SHIRINA_MM", "VYSOTA_MM"] as $code) :
                if (strlen($arResult["ITEM"]["PROPERTIES"][$code]["VALUE"]) == 0) continue;
                if (strlen($propValue) > 0) {
                    $propTitle .= "x";
                    $propValue .= " x ";
                }
                $propTitle .= strtoupper(mb_substr($arResult["ITEM"]["PROPERTIES"][$code]["NAME"], 0, 1));
                $propValue .= $arResult["ITEM"]["PROPERTIES"][$code]["VALUE"];
                ?>
            <?endforeach?>
            <?if (strlen($propValue) > 0) :?>
                <div class="table_list-desc-item"><span><?=$propTitle?> (мм):</span> <?=$propValue?></div>
            <?endif?>
            <?foreach (["VES_KG", "OBEM_L"] as $code) :
                if (
                        !isset($arResult["ITEM"]["PROPERTIES"][$code])
                        || strlen($arResult["ITEM"]["PROPERTIES"][$code]["VALUE"]) == 0
                ) continue;
                ?>
                <div class="table_list-desc-item"><span><?=$arResult["ITEM"]["PROPERTIES"][$code]["NAME"]?>:</span> <?=$arResult["ITEM"]["PROPERTIES"][$code]["VALUE"]?></div>
            <?endforeach?>
        </div>
    </div>
    <div class="table_list-wrap">
        <?if ($arPrice["PRICE"] > 0) :?>
            <div class="table_list-info border full">
                <div class="table_list-price_wrap">
                    <?if ($arParams['SHOW_OLD_PRICE'] == "Y" && $arPrice["BASE_PRICE"] > $arPrice["PRICE"]) :?>
                        <s><?=$arPrice['PRINT_RATIO_BASE_PRICE']?></s><br>
                    <?endif?>
                    <div class="table_list-price text-line">
                        <?if ($arResult["OFFER"]['PROPERTIES']['PRICE_FROM']['VALUE'] === 'Y') :?>
                            <span>от</span>
                        <?endif?>
                        <?=$arPrice["PRINT_RATIO_PRICE"]?>
                    </div>
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
        <div class="table_list-info full hide_in_list<?if ($arPrice["PRICE"] <= 0) :?> margin<?endif?>">
            <?if (isset($arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) && strlen($arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) > 0) :?>
                <div class="table_list-desc-item"><span><?=$arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["NAME"]?>:</span> <?=$arResult["OFFER"]["PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></div>
            <?endif?>
            <?if (is_array($arResult["OFFER"]["QNT_INFO"])) :?>
                <div class="table_list-status <?=$arResult["OFFER"]["QNT_INFO"]["CLASS"]?>"><?=$arResult["OFFER"]["QNT_INFO"]["MSG_TEXT"]?></div>
            <?endif?>
        </div>
        <div class="show_in_list">
            <?if ($arResult["OFFER"]["CAN_BUY"]) :?>
                <div class="table_list-info">
                    <a href="#" data-popup-open="#buy-one-click">
                        <i class="icon buy_one_click"></i>
                        <span>Купить в 1 клик</span>
                    </a>
                </div>
            <?endif?>
            <?if ($arParams["DISPLAY_COMPARE"] == "Y") :?>
                <div class="table_list-info">
                    <a href="#" data-entity="favorite" data-id="<?=$arResult["ITEM"]["ID"]?>">
                        <i class="icon favorite"></i>
                        <span>В избранное</span>
                    </a>
                </div>
                <div class="table_list-info">
                    <a href="#" data-entity="compare" data-id="<?=$arResult["OFFER"]["ID"]?>">
                        <i class="icon compare"></i>
                        <span>Сравнить</span>
                    </a>
                </div>
            <?endif?>
        </div>
    </div>
</div>
<div class="hide_in_list table_list-footer">
    <div class="full">
        <?
        $hasActiveProps = false;
        foreach (["VES_KG", "OBEM_L"] as $code) :
            if (
                    !isset($arResult["ITEM"]["PROPERTIES"][$code])
                    || strlen($arResult["ITEM"]["PROPERTIES"][$code]["VALUE"]) == 0
            ) continue;
            $hasActiveProps = true;
            ?>
            <div><small><?=$arResult["ITEM"]["PROPERTIES"][$code]["NAME"]?>:</small> <?=$arResult["ITEM"]["PROPERTIES"][$code]["VALUE"]?></div>
        <?endforeach?>
    </div>
    <div class="<?if ($hasActiveProps) :?>border-top <?endif?>full">
        <?if ($arResult["OFFER"]["CAN_BUY"]) :?>
            <div class="table_list-info">
                <a href="#" data-popup-open="#buy-one-click">
                    <i class="icon buy_one_click"></i>
                    <span>Купить в 1 клик</span>
                </a>
            </div>
        <?endif?>
        <?if ($arParams["DISPLAY_COMPARE"] == "Y") :?>
            <div class="table_list-info">
                <a href="#" data-entity="favorite" data-id="<?=$arResult["ITEM"]["ID"]?>">
                    <i class="icon favorite"></i>
                    <span>В избранное</span>
                </a>
            </div>
            <div class="table_list-info">
                <a href="#" data-entity="compare" data-id="<?=$arResult["OFFER"]["ID"]?>">
                    <i class="icon compare"></i>
                    <span>Сравнить</span>
                </a>
            </div>
        <?endif?>
    </div>
</div>
<script>
    if (typeof window.catalogElement == "function") {
        var obCatalogElement_<?=$arResult["OFFER"]["ID"]?> = new window.catalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    }
</script>
