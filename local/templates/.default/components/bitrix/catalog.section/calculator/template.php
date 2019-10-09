<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="block_wrapper">
    <?if ($arResult["SECTIONS_COUNT"] > 0) :?>
        <div class="cart">
            <div class="cart_img">
                <div class="title-2">Параметры стеллажа</div>
                <form id="catalog-calculator-main" class="horizontal_form" flex-align="center">
                    <div class="horizontal_form-item" flex-align="center">
                        <label>Ширина, мм</label>
                        <input type="text" onchange="obCalculator.setWidth(this)" class="small">
                    </div>
                    <div class="horizontal_form-item" flex-align="center">
                        <label>Высота, мм</label>
                        <input type="text" onchange="obCalculator.setHeight(this)" class="small">
                    </div>
                    <div class="horizontal_form-item" flex-align="center">
                        <label>Глубина, мм</label>
                        <input type="text" onchange="obCalculator.setLength(this)" class="small">
                    </div>
                </form>
                <div class="horizontal_form hidden-lg hidden-md hidden-xs" flex-align="center">
                    <div class="col-lg-24" flex-align="center">
                        <div class="horizontal_form-item">
                            <label>Высота основания полки, мм</label>
                            <input type="text" name="item_height" value="10" class="small" onchange="obCalculatorRender.setRackItemHeight(this)">
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
                <select class="full" onchange="obCalculator.getCatalogItems(this)">
                    <option value="-1">выбрать</option>
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
    obCalculator.setColor(<?=CUtil::PhpToJSObject($arResult["COLORS"])?>)
</script>