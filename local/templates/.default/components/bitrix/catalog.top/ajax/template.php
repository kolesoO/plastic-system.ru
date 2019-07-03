<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

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