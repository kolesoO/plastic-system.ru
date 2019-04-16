<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$this->setFrameMode(true);
?>

<div id="catalog-filter-popup" class="popup" data-animate>
    <div class="popup_wrapper">
        <div class="popup_content animate-start js-popup_content">
            <a href="#" class="popup_content-close" data-popup-close><i class="icon close"></i></a>
            <div class="title-3"><?=GetMessage("CT_BCSF_FILTER_TITLE")?></div>
            <form name="<?=$arResult["FILTER_NAME"]."_form"?>?>" action="<?=$arResult["FORM_ACTION"]?>" method="get" class="catalog_filter">
                <?if ($arParams["COMPONENT_CONTAINER_ID"]) :?>
                    <input type="hidden" name="bxajaxid" value="<?=$arParams["COMPONENT_CONTAINER_ID"]?>">
                <?endif?>
                <?foreach($arResult["HIDDEN"] as $arItem):?>
                    <input type="hidden" name="<?=$arItem["CONTROL_NAME"]?>" id="<?=$arItem["CONTROL_ID"]?>" value="<?=$arItem["HTML_VALUE"]?>" />
                <?endforeach;?>
                <?
                //цены
                foreach ($arResult["ITEMS"] as $key=>$arItem) :?>
                    <?if (isset($arItem["PRICE"])) :
                        if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                            continue;
                        ?>
                        <div class="catalog_filter-item">
                            <div class="catalog_filter-item-title"><?=GetMessage("CT_BCSF_FILTER_PRICE_TITLE")?></div>
                            <div class="catalog_filter-value price">
                                <div class="catalog_filter-value-item">
                                    <input
                                            name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                            id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                            type="text"
                                            placeholder="<?=GetMessage("CT_BCSF_FILTER_FROM")." ".number_format(floatval($arItem["VALUES"]["MIN"]["VALUE"]), 0, ".", " ")?>"
                                            onkeyup="smartFilter.keyup(this)"
                                            value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                    >
                                </div>
                                <div class="catalog_filter-value-item">
                                    <input
                                            name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                            id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                            type="text"
                                            placeholder="<?=GetMessage("CT_BCSF_FILTER_TO")." ".number_format(floatval($arItem["VALUES"]["MAX"]["VALUE"]), 0, ".", " ")?>"
                                            onkeyup="smartFilter.keyup(this)"
                                            value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                    >
                                </div>
                            </div>
                        </div>
                        <?break;?>
                    <?endif?>
                <?endforeach?>
                <?
                //остальные свойства
                foreach ($arResult["ITEMS"] as $key=>$arItem) :
                    if(empty($arItem["VALUES"]) || isset($arItem["PRICE"]))
                        continue;
                    if ($arItem["DISPLAY_TYPE"] == "A" && ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0))
                        continue;
                    $arCur = current($arItem["VALUES"]);
                    ?>
                    <div class="catalog_filter-item">
                        <div class="catalog_filter-item-title"><?=$arItem["NAME"]?></div>
                        <?switch ($arItem["DISPLAY_TYPE"]) {
                            case "B": //числа ?>
                                <div class="catalog_filter-value price">
                                    <div class="catalog_filter-value-item">
                                        <input
                                                name="<?=$arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                                id="<?=$arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                                type="text"
                                                placeholder="<?=GetMessage("CT_BCSF_FILTER_FROM")." ".number_format(floatval($arItem["VALUES"]["MIN"]["VALUE"]), 0, ".", " ")?>"
                                                value="<?=$arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                                onkeyup="smartFilter.keyup(this)"
                                        >
                                    </div>
                                    <div class="catalog_filter-value-item">
                                        <input
                                                name="<?=$arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                                id="<?=$arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                                type="text"
                                                placeholder="<?=GetMessage("CT_BCSF_FILTER_TO")." ".number_format(floatval($arItem["VALUES"]["MAX"]["VALUE"]), 0, ".", " ")?>"
                                                value="<?=$arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                                onkeyup="smartFilter.keyup(this)"
                                        >
                                    </div>
                                </div>
                                <?break;
                            case "F": //флажки
                                $checkedItemExist = false;
                                foreach ($arItem["VALUES"] as $val => $ar) {
                                    if ($ar["CHECKED"]) {
                                        $checkedItemExist = true;
                                        break;
                                    }
                                }
                                ?>
                                <div class="catalog_filter-value">
                                    <div class="catalog_filter-value-item">
                                        <input
                                                id="all_<?=$arCur["CONTROL_ID"]?>"
                                                type="checkbox"
                                            <?if (!$checkedItemExist) :?> checked<?endif?>
                                                name="<?=$arCur["CONTROL_NAME_ALT"]?>"
                                                value=""
                                                onclick="smartFilter.click(this)"
                                        >
                                        <label for="all_<?=$arCur["CONTROL_ID"]?>">Любой</label>
                                    </div>
                                    <?foreach ($arItem["VALUES"] as $val => $ar) :?>
                                        <div class="catalog_filter-value-item">
                                            <input
                                                    type="checkbox"
                                                    value="<?=$ar["HTML_VALUE"]?>"
                                                    name="<?=$ar["CONTROL_NAME"]?>"
                                                    id="<?=$ar["CONTROL_ID"]?>"
                                                <?if ($ar["CHECKED"]) :?> checked<?endif?>
                                                    onclick="smartFilter.click(this)"
                                            >
                                            <label for="<?=$ar["CONTROL_ID"]?>">
                                                <span><?=$ar["VALUE"]?></span>
                                                <?if (isset($ar["ELEMENT_COUNT"])) :?>
                                                    <sup><?=$ar["ELEMENT_COUNT"]?></sup>
                                                <?endif?>
                                            </label>
                                        </div>
                                    <?endforeach?>
                                </div>
                                <?break;
                        }?>
                    </div>
                <?endforeach?>
                <div id="modef"<?if(!isset($arResult["ELEMENT_COUNT"])) echo ' style="display:none"';?> class="catalog_filter-item">
                    <p><a href="<?=$arResult["FILTER_URL"]?>" class="col-xs-24 form_button color"><?=GetMessage("CT_BCSF_FILTER_SHOW")?></a></p>
                    <span class="table_list-desc-item">
                        <span><?=GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<b id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</b>'));?></span>
                    </span>
                </div>
                <div id="modef_del"<?if (!$arResult["IS_APPLIED"]) :?> style="display:none"<?endif?> class="catalog_filter-item">
                    <a href="<?=$arResult["SEF_DEL_FILTER_URL"]?>" class="col-xs-24 form_button"><?=GetMessage("CT_BCSF_DEL_FILTER")?></a>
                </div>
                <input
                        class="hidden-xs"
                        type="submit"
                        id="set_filter"
                        name="set_filter"
                        value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
                >
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>