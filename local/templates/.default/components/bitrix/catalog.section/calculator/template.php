<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="block_wrapper">
    <div class="cart">
        <div class="cart_img">
            <div class="title-2">Параметры стеллажа</div>
            <form id="catalog-calculator-main" class="horizontal_form" flex-align="center">
                <div class="horizontal_form-item" flex-align="center">
                    <label>Ширина, мм</label>
                    <select name="PROPERTY_<?=$code?>_VALUE" class="small" onchange="obAjax.getCatalogCalcItems(this, 'catalog-calculator-sub_main')">
                        <option value="">выбрать</option>
                        <?foreach ($arProp as $arValue) :?>
                            <option value="<?=$arValue["VALUE"]?>"><?=$arValue["VALUE"]?></option>
                        <?endforeach?>
                    </select>
                </div>
            </form>
            <div class="horizontal_form" flex-align="center">
                <div class="horizontal_form-item" flex-align="center">
                    <label>Полка 1, высота мм</label>
                    <input type="text" name="<=PROPERTY_VYSOTA_MM_VALUE" value="" class="small">
                </div>
                <div class="horizontal_form-item">
                    <a href="#" class="link dashed">Добавить полку</a>
                </div>
            </div>
            <div class="rack-wrap">
                <div class="rack">
                    <div class="rack-header"></div>
                    <div id="rack" class="rack-content">
                        <div class="rack_item">
                            <div class="tray_item">
                                <a href="#" class="tray_item-close"><i class="icon icon-close"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cart_desc">
            <div class="title-2">Лотки и контейнеры</div>
            <?if ($arResult["SECTIONS_COUNT"] > 0) :?>
                <form id="catalog-calculator-sub_main">
                    <select name="SECTION_ID" class="full" onchange="obAjax.getCatalogCalcItems(this, 'catalog-calculator-main')">
                        <option value="">выбрать</option>
                        <?foreach ($arResult["SECTIONS"] as $arSection) :?>
                            <option value="<?=$arSection["ID"]?>"><?=$arSection["NAME"]?></option>
                        <?endforeach?>
                    </select>
                </form>
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