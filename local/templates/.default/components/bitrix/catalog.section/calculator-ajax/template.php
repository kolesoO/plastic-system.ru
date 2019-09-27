<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if ($arResult["ITEMS_COUNT"] > 0) :?>
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
<?else:?>
    <p>Товары не найдены</p>
<?endif?>