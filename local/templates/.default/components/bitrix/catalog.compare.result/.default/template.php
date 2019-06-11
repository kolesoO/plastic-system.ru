<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$isAjax = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isAjax = (
        (isset($_POST['ajax_action']) && $_POST['ajax_action'] == 'Y')
        || (isset($_POST['compare_result_reload']) && $_POST['compare_result_reload'] == 'Y')
    );
}

$hasArrows = $arParams["DEVICE_TYPE"] == "DESKTOP";
?>

<div class="block_wrapper compare_list" id="bx_catalog_compare_block">
    <?if ($isAjax) $APPLICATION->RestartBuffer();?>
    <div
        class="compare_list js-slider"
        data-autoplay="false"
        data-autoplaySpeed="5000"
        data-infinite="false"
        data-speed="500"
        data-arrows="<?=($hasArrows ? "true" : "false")?>"
        data-dots="false"
        data-slidesToShow="<?=$arParams["LINE_ELEMENT_COUNT"]?>"
        data-slidesToScroll="1"
        <?if ($hasArrows) :?>
            data-showArrows="true"
            data-prevArrow="<a href='javascript:void(0)' class='color_arrow-left'></a>"
            data-nextArrow="<a href='javascript:void(0)' class='color_arrow-right'></a>"
        <?endif?>
        data-fixed="true"
    >
        <?foreach ($arResult["ITEMS"] as $elemKey => $arElement) :?>
            <div class="compare_list-item">
                <a
                    href="javascript:void(0)"
                    class="close_wrap"
                    title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>"
                    onclick="CatalogCompareObj.delete('<?=CUtil::JSEscape($arElement['~DELETE_URL'])?>');"
                >
                    <i class="icon close"></i>
                </a>
                <div class="compare_list-img">
                    <img src="<?=(isset($arResult["SHOW_FIELDS"]["PREVIEW_PICTURE"]["SRC"]) ? $arResult["SHOW_FIELDS"]["PREVIEW_PICTURE"]["SRC"] : SITE_TEMPLATE_PATH."/images/no-image.png")?>">
                </div>
                <div class="compare_list-desc">
                    <?if (isset($arResult["SHOW_FIELDS"]["NAME"])) :?>
                        <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="compare_list-title compare_list-desc-item"><?=$arElement["NAME"]?></a>
                    <?endif?>
                    <?if (isset($arElement['MIN_PRICE']) && is_array($arElement['MIN_PRICE'])) :?>
                        <div class="compare_list-price compare_list-desc-item"><?=$arElement['MIN_PRICE']['PRINT_DISCOUNT_VALUE']?></div>
                    <?elseif (!empty($arElement['PRICE_MATRIX']) && is_array($arElement['PRICE_MATRIX'])) :
                        $matrix = $arElement['PRICE_MATRIX'];
                        $rows = $matrix['ROWS'];
                        $rowsCount = count($rows);
                        ?>
                        <?if ($rowsCount > 1) :?>
                            <?foreach ($rows as $index => $rowData) :?>
                                <div class="compare_list-price compare_list-desc-item"><?=\CCurrencyLang::CurrencyFormat($matrix['MIN_PRICES'][$index]['PRICE'], $matrix['MIN_PRICES'][$index]['CURRENCY']);?></div>
                            <?endforeach?>
                        <?elseif ($rowsCount > 0) :
                            $currentPrice = current($matrix['MIN_PRICES']);
                            ?>
                            <div class="compare_list-price compare_list-desc-item"><?=\CCurrencyLang::CurrencyFormat($currentPrice['PRICE'], $currentPrice['CURRENCY'])?></div>
                            <?unset($currentPrice);?>
                        <?else:?>
                            <div class="compare_list-price compare_list-desc-item">-</div>
                        <?endif?>
                        <?unset($rowsCount, $rows, $matrix);?>
                    <?endif?>
                    <?if (!empty($arResult["SHOW_PROPERTIES"])) :?>
                        <?foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty) :
                            $propTitle = $elemKey == 0 ? $arProperty["NAME"] : "&nbsp;";
                            $value = is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"];
                            if (strlen($value) == 0) $value = "-";
                            ?>
                            <div class="compare_list-desc-item">
                                <label class="compare_list-label" data-fixed_target><?=$propTitle?></label>
                                <span><?=$value?></span>
                            </div>
                        <?endforeach?>
                    <?endif?>
                    <?if (!empty($arResult["SHOW_OFFER_PROPERTIES"])) :?>
                        <?foreach ($arResult["SHOW_OFFER_PROPERTIES"] as $code => $arProperty) :
                            $propTitle = $elemKey == 0 ? $arProperty["NAME"] : "&nbsp;";
                            $value = is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"];
                            if (strlen($value) == 0) $value = "-";
                            ?>
                            <div class="compare_list-desc-item">
                                <label class="compare_list-label" data-fixed_target><?=$propTitle?></label>
                                <span><?=$value?></span>
                            </div>
                        <?endforeach?>
                    <?endif?>
                </div>
            </div>
        <?
        endforeach;
        unset($arElement);
        ?>
    </div>
    <?if ($isAjax) die();?>
</div>
<script type="text/javascript">
    var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("bx_catalog_compare_block", '<?=CUtil::JSEscape($arResult['~COMPARE_URL_TEMPLATE']); ?>');
</script>