<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Корзина");
$APPLICATION->SetPageProperty("header_section-class", "section");

//price update - fix
if (\Bitrix\Main\Loader::includeModule('sale')) {
    $arBasketItems = [];
    $rsItems = \CSaleBasket::GetList(
        [],
        [""],
        false,
        false,
        ["ID", "PRODUCT_ID", "QUANTITY"]
    );
    while ($item = $rsItems->fetch()) {
        $arBasketItems[$item["PRODUCT_ID"]] = $item;
    }
    if (count($arBasketItems) > 0) {
        $rsItem = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU,
                "ID" => array_keys($arBasketItems)
            ],
            false,
            false,
            ["IBLOCK_ID", "ID", "NAME", "PREVIEW_PICTURE", "XML_ID", "CATALOG_GROUP_" . PRICE_ID]
        );
        while ($arItem = $rsItem->fetch()) {
            if ($arPrice = \CCatalogProduct::GetOptimalPrice(
                $arItem["ID"],
                $arBasketItems[$arItem["ID"]]["QUANTITY"],
                $USER->GetUserGroupArray(),
                "N",
                [
                    [
                        "ID" => $arItem["CATALOG_PRICE_ID_" . PRICE_ID],
                        "PRICE" => $arItem["CATALOG_PRICE_" . PRICE_ID],
                        "CURRENCY" => "RUB",
                        "CATALOG_GROUP_ID" => PRICE_ID
                    ]
                ]
            )) {
                \CSaleBasket::Update($arBasketItems[$arItem["ID"]]["ID"], [
                    "PRODUCT_PRICE_ID" => $arPrice["PRICE"]["ID"],
                    "PRICE" => $arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"],
                    "BASE_PRICE" => $arPrice["RESULT_PRICE"]["BASE_PRICE"],
                    "DISCOUNT_PRICE" => $arPrice["RESULT_PRICE"]["DISCOUNT"],
                    "DISCOUNT_NAME" => $arPrice["DISCOUNT"]["NAME"]
                ]);
            }
        }
    }
}
//end

if (DEVICE_TYPE == "MOBILE") {
    $tmp = ".default-mobile";
} else {
    $tmp = ".default";
}

$APPLICATION->IncludeComponent(
    "bitrix:sale.basket.basket",
    $tmp,
    Array(
        "ACTION_VARIABLE" => "action",
        "AUTO_CALCULATION" => "Y",
        "TEMPLATE_THEME" => "blue",
        "COLUMNS_LIST" => array("NAME", "DISCOUNT", "WEIGHT", "DELETE", "PRICE", "QUANTITY"),
        "COMPONENT_TEMPLATE" => ".default",
        "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
        "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
        "GIFTS_CONVERT_CURRENCY" => "Y",
        "GIFTS_HIDE_BLOCK_TITLE" => "N",
        "GIFTS_HIDE_NOT_AVAILABLE" => "N",
        "GIFTS_MESS_BTN_BUY" => "Выбрать",
        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
        "GIFTS_PAGE_ELEMENT_COUNT" => "4",
        "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",
        "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
        "GIFTS_SHOW_IMAGE" => "Y",
        "GIFTS_SHOW_NAME" => "Y",
        "GIFTS_SHOW_OLD_PRICE" => "Y",
        "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
        "GIFTS_PLACE" => "BOTTOM",
        "HIDE_COUPON" => "N",
        "OFFERS_PROPS" => array("SIZES_SHOES", "SIZES_CLOTHES"),
        "PATH_TO_ORDER" => "/checkout/",
        "EMPTY_REDIRECT_PATH" => "/product-category/",
        "PRICE_VAT_SHOW_VALUE" => "N",
        "QUANTITY_FLOAT" => "N",
        "SET_TITLE" => "N",
        "TEMPLATE_THEME" => "blue",
        "USE_GIFTS" => "N",
        "USE_PREPAYMENT" => "N",
        "TOTAL_BLOCK_DISPLAY" => ["bottom"],
        "SHOW_FILTER" => "N",
        "SHOW_RESTORE" => "N"
    )
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');