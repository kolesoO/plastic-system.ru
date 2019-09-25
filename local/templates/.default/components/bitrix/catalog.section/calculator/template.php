<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="block_wrapper">
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
            <div class="horizontal_form" flex-align="center">
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
                        <label>Высота, мм</label>
                        <input id="item_height" type="text" name="item_height" value="" class="small">
                    </div>
                    <div class="horizontal_form-item">
                        <a href="#" class="link dashed" onclick="obCalculator.addItem('#item_height')">Добавить полку</a>
                    </div>
                </div>
            </div>
            <div class="rack-wrap">
                <div class="rack">
                    <svg xmlns="http://www.w3.org/2000/svg" width="344" height="33" viewBox="0 0 344 33"><defs><style>.a{fill:#fff;}.b{fill:#303030;}.c{fill:none;stroke:#303030;stroke-miterlimit:10;stroke-width:2px;}</style></defs><path class="a" d="M337,32V23H7v9H1V1H7V9H337V1h6V32Z"/><path class="b" d="M342,2V31h-4V22H6v9H2V2H6v8H338V2h4m2-2h-8V8H8V0H0V33H8V24H336v9h8V0Z"/><line class="c" x2="325" transform="translate(10 14)"/><line class="c" x2="325" transform="translate(10 19)"/></svg>
                    <div id="rack-content" class="rack-content"></div>
                </div>
            </div>
        </div>
        <div class="cart_desc">
            <div class="title-2">Лотки и контейнеры</div>
            <?if ($arResult["SECTIONS_COUNT"] > 0) :?>
                <select class="full" onchange="obCalculator.getCatalogItems(this)">
                    <option value="">выбрать</option>
                    <?foreach ($arResult["SECTIONS"] as $arSection) :?>
                        <option value="<?=$arSection["ID"]?>"><?=$arSection["NAME"]?></option>
                    <?endforeach?>
                </select>
                <br>
            <?endif?>
            <div id="<?=$arParams["WRAP_ID"]?>">
                <?if ($arResult["ITEMS_COUNT"] > 0) :?>
                    <!--p>
                        <span>SK 315019 шт.</span><br>
                        <span>Стоимость выбранных лотков/контейнеров: 2242 руб.</span>
                    </p-->
                    <a href="#" class="table_list-basket full" onclick="obAjax.addToBasketMany(event)">
                        <i class="icon basket-white"></i>
                        <span>В корзину</span>
                    </a>
                    <div class="calculator_list">
                        <?foreach ($arResult["ITEMS"] as $arItem) {
                            $APPLICATION->IncludeComponent(
                                "bitrix:catalog.item",
                                "small",
                                [
                                    "RESULT" => [
                                        "ITEM" => $arItem,
                                        "OFFER_KEY" => 0,
                                        "OFFERS_LIST" => $arItem["OFFERS"]
                                    ],
                                    "PARAMS" => $arResult["ORIGINAL_PARAMETERS"],
                                    "PRICES" => $arResult["PRICES"]
                                ],
                                null,
                                ['HIDE_ICONS' => 'Y']
                            );
                        }?>
                    </div>
                <?else:?>
                    <p>Товары не найдены</p>
                <?endif?>
            </div>
        </div>
    </div>
</div>
<script>var obCatalogCalcItemsParams = <?=CUtil::PhpToJSObject(array_merge(["target_id" => $arParams["WRAP_ID"]], $arParams))?>;</script>