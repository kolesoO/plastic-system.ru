<?
namespace kDevelop\Ajax;

use kDevelop\Settings\Store;

class Basket
{
    use MsgHandBook;

    /**
     * @param array $arProp
     * @return array
     */
    private static function getBasketProperty(array $arProp)
    {
        return [
            "NAME" => $arProp["NAME"],
            "CODE" => $arProp["CODE"],
            "VALUE" => $arProp["VALUE"]
        ];
    }

    /**
     * @param array $arParams
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public static function add(array $arParams)
    {
        $arReturn = ["js_callback" => "addToBasketCallBack"];

        if (is_array($arParams["offer_id"]) && count($arParams["offer_id"]) > 0) {
            if (isset($arParams["price_id"]) && strlen($arParams["price_id"]) > 0) {
                if (\Bitrix\Main\Loader::includeModule("sale")) {
                    $arParams["qnt"] = intval($arParams["qnt"]);

                    //qnt
                    if ($arParams["qnt"] == 0) {
                        $arParams["qnt"] = count($arParams["offer_id"]);
                    }

                    $otherStoresInfo = array_filter(
                        Store::getStoresInfo([
                            'ACTIVE' => 'Y',
                            '!UF_PRICE_ID' => false,
                            '!SITE_ID' => false,
                        ]),
                        static function (array $storeInfo) use ($arParams) {
                            return $storeInfo[1] != $arParams["price_id"];
                        }
                    );
                    $defaultCurrencyId = Store::getDefaultCurrencyId();

                    $itemSelect = [
                        "IBLOCK_ID", "ID", "NAME",
                        "PREVIEW_PICTURE", "XML_ID",
                        "CATALOG_GROUP_" . $arParams["price_id"],
                    ];
                    foreach ($otherStoresInfo as $storeInfo) {
                        $itemSelect = array_merge($itemSelect, ['CATALOG_GROUP_' . $storeInfo[1]]);
                    }

                    $rsItem = \CIBlockElement::GetList(
                        [],
                        [
                            "IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU,
                            "ID" => $arParams["offer_id"],
                        ],
                        false,
                        false,
                        $itemSelect
                    );

                    if ($rsItem->SelectedRowsCount() == 0) {
                        $arReturn["msg"] = self::getMsg("ITEMS_NOT_AVAILABLE");
                    } else {
                        while ($arItem = $rsItem->fetch()) {
                            if ($arItem["CATALOG_AVAILABLE"] != "Y" || floatval($arItem["CATALOG_PRICE_".$arParams["price_id"]]) == 0) {
                                $arReturn["msg"] = self::getMsg("ITEMS_NOT_AVAILABLE", $arItem["NAME"]);
                                continue;
                            }

                            //add to basket for all other stores
                            foreach ($otherStoresInfo as $storeInfo) {
                                self::processed(
                                    $arItem,
                                    $arParams["qnt"],
                                    $storeInfo[1],
                                    $storeInfo[2] ?? $defaultCurrencyId,
                                    $storeInfo[3]
                                );
                            }

                            //add to basket for current store
                            $basketId = self::processed(
                                $arItem,
                                $arParams["qnt"],
                                $arParams["price_id"],
                                CURRENCY_ID,
                                SITE_ID
                            );

                            if ($basketId) {
                                $arReturn["basket_id"][] = $basketId;
                                $arReturn["msg"] = self::getMsg("ADD_TO_BASKET_SUCCESS");
                            } else {
                                $arReturn["msg"] = self::getMsg("ADD_TO_BASKET_ERROR", $arItem["NAME"]);
                            }
                        }
                    }
                } else {
                    $arReturn["msg"] = self::getMsg("MODULE_SALE_NOT_INSTALLED");
                }
            } else {
                $arReturn["msg"] = self::getMsg("PRICE_TYPE_NOT_FOUND");
            }
        } else {
            $arReturn["msg"] = self::getMsg("ITEMS_NOT_FOUND");
        }

        return $arReturn;
    }

    /**
     * @param array $arItem
     * @param int $qnt
     * @param string|int$priceId
     * @param string $currencyId
     * @param string $siteId
     * @return int|null
     */
    private static function processed(array $arItem, int $qnt, $priceId, string $currencyId, string $siteId): ?int
    {
        global $USER;

        //general price info
        $arPrice = \CCatalogProduct::GetOptimalPrice(
            $arItem["ID"],
            $qnt,
            $USER->GetUserGroupArray(),
            "N",
            [
                [
                    "ID" => $arItem["CATALOG_PRICE_ID_".$priceId],
                    "PRICE" => $arItem["CATALOG_PRICE_".$priceId],
                    "CURRENCY" => $currencyId,
                    "CATALOG_GROUP_ID" => $priceId
                ]
            ]
        );

        return \CSaleBasket::Add([
            "PRODUCT_ID" => $arItem["ID"],
            "PRODUCT_PRICE_ID" => $arPrice["PRICE"]["ID"],
            "PRICE_TYPE_ID" => $arPrice["RESULT_PRICE"]["PRICE_TYPE_ID"],
            "PRICE" => $arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"],
            "BASE_PRICE" => $arPrice["RESULT_PRICE"]["BASE_PRICE"],
            //"CUSTOM_PRICE" => "Y",
            "CURRENCY" => $currencyId,
            "WEIGHT" => $arItem["CATALOG_WEIGHT"],
            "QUANTITY" => $qnt,
            "LID" => $siteId,
            "DELAY" => "N",
            "CAN_BUY" => "Y",
            "NAME" => $arItem["NAME"],
            "PRODUCT_XML_ID" => $arItem["XML_ID"],
            "MODULE" => "catalog",
            "NOTES" => "",
            "PRODUCT_PROVIDER_CLASS" => "\kDevelop\Service\CatalogProductProvider",
            //"IGNORE_CALLBACK_FUNC" => "",
            //"DISCOUNT_PRICE" => $arPrice["RESULT_PRICE"]["DISCOUNT"],
            //"DISCOUNT_NAME" => $arPrice["DISCOUNT"]["NAME"],
            //"DISCOUNT_VALUE" => $arPrice["RESULT_PRICE"]["DISCOUNT"],
            //"DISCOUNT_COUPON" => "",
            "PROPS" => []
        ]);
    }
}
