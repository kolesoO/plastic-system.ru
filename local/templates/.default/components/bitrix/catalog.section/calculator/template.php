<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="block_wrapper">
    <?if ($arResult["SECTIONS_COUNT"] > 0) :?>
        <div class="cart">
            <div class="cart_img">
                <div class="title-2">Параметры стеллажа</div>
                <form id="catalog-calculator-main" class="horizontal_form" flex-align="center">
                    <div class="horizontal_form-item" flex-align="center">
                        <label>Ширина, мм</label>
                        <?if (isset($arResult["CALCULATOR_SETTINGS"]["CALC_WIDTH"]["VALUE"]) && is_array($arResult["CALCULATOR_SETTINGS"]["CALC_WIDTH"]["VALUE"])) :?>
                            <select class="small" onchange="obCalculator.setWidth(this)">
                                <option value="">не выбрано</option>
                                <?foreach ($arResult["CALCULATOR_SETTINGS"]["CALC_WIDTH"]["VALUE"] as $value) :?>
                                    <option value="<?=$value?>"><?=$value?></option>
                                <?endforeach?>
                            </select>
                        <?else:?>
                            <input type="text" onchange="obCalculator.setWidth(this)" class="small">
                        <?endif?>
                    </div>
                    <div class="horizontal_form-item" flex-align="center">
                        <label>Высота, мм</label>
                        <?if (isset($arResult["CALCULATOR_SETTINGS"]["CALC_HEIGHT"]["VALUE"]) && is_array($arResult["CALCULATOR_SETTINGS"]["CALC_HEIGHT"]["VALUE"])) :?>
                            <select class="small" onchange="obCalculator.setHeight(this)">
                                <option value="">не выбрано</option>
                                <?foreach ($arResult["CALCULATOR_SETTINGS"]["CALC_HEIGHT"]["VALUE"] as $value) :?>
                                    <option value="<?=$value?>"><?=$value?></option>
                                <?endforeach?>
                            </select>
                        <?else:?>
                            <input type="text" onchange="obCalculator.setHeight(this)" class="small">
                        <?endif?>
                    </div>
                    <div class="horizontal_form-item" flex-align="center">
                        <label>Глубина, мм</label>
                        <?if (isset($arResult["CALCULATOR_SETTINGS"]["CALC_LENGTH"]["VALUE"]) && is_array($arResult["CALCULATOR_SETTINGS"]["CALC_LENGTH"]["VALUE"])) :?>
                            <select class="small" onchange="obCalculator.setLength(this)">
                                <option value="">не выбрано</option>
                                <?foreach ($arResult["CALCULATOR_SETTINGS"]["CALC_LENGTH"]["VALUE"] as $value) :?>
                                    <option value="<?=$value?>"><?=$value?></option>
                                <?endforeach?>
                            </select>
                        <?else:?>
                            <input type="text" onchange="obCalculator.setLength(this)" class="small">
                        <?endif?>
                    </div>
                </form>
                <div class="horizontal_form hidden-lg hidden-md hidden-xs" flex-align="center">
                    <div class="col-lg-24" flex-align="center">
                        <div class="horizontal_form-item">
                            <label>Высота основания полки, мм</label>
                            <input
                                    type="text"
                                    name="item_height"
                                    value="10"
                                    class="small"
                                    onchange="obCalculatorRender.setRackItemHeight(this)"
                            >
                        </div>
                    </div>
                </div>
                <div class="horizontal_form" flex-align="center">
                    <div class="col-lg-24" flex-align="center">
                        <div class="horizontal_form-item">
                            <label>Высота полки, мм</label>
                            <input id="item_height" type="text" name="item_height" value="" class="small">
                        </div>
                        <div class="horizontal_form-item">
                            <a href="#" class="link dashed" onclick="obCalculator.addItem('#item_height')">Добавить полку</a>
                        </div>
                        <div class="horizontal_form-item">
                            <small>Для выбора нужной полки кликните на нее на схеме</small>
                        </div>
                    </div>
                </div>
                <div class="rack-wrap" align="center">
                    <div class="rack">
                        <div class="rack-head"></div>
                        <div id="rack-content" class="rack-content"></div>
                    </div>
                </div>
            </div>
            <div class="cart_desc">
                <div class="title-2">Лотки и контейнеры</div>
                <select id="section_id" class="full" onchange="obCalculator.getCatalogItems(this)">
                    <option value="0">выбрать</option>
                    <?foreach ($arResult["SECTIONS"] as $arSection) :?>
                        <option value="<?=$arSection["ID"]?>"><?=$arSection["NAME"]?></option>
                    <?endforeach?>
                </select>
                <br>
                <p id="article_list"></p>
                <p>
                    <span>Добалено - <span id="full_count">0</span> шт.</span><br>
                    <span>Стоимость выбранных лотков/контейнеров: <span id="full_price">0</span> руб.</span>
                </p>
                <a href="#" class="table_list-basket full" onclick="obCalculator.addToBasketMany(event)">
                    <i class="icon basket-white"></i>
                    <span>В корзину</span>
                </a>
                <div id="<?=$arParams["WRAP_ID"]?>" class="calculator_list"></div>
            </div>
        </div>
    <?else:?>
        <span>Не найдено активных разделов каталога, доступных калькулятору</span>
    <?endif?>
</div>
<script>
    var obCatalogCalcItemsParams = <?=CUtil::PhpToJSObject(array_merge(["target_id" => $arParams["WRAP_ID"]], $arParams))?>;
    obCalculator.setColor(<?=CUtil::PhpToJSObject($arResult["COLORS"])?>);
    obCalculatorRender.rackItemHeight = parseFloat("<?=strlen($arResult["CALCULATOR_SETTINGS"]["CALC_ITEM_HEIGHT"]["VALUE"]) > 0 ? $arResult["CALCULATOR_SETTINGS"]["CALC_ITEM_HEIGHT"]["VALUE"] : $arResult["CALCULATOR_SETTINGS"]["CALC_ITEM_HEIGHT"]["DEFAULT_VALUE"]?>");
</script>