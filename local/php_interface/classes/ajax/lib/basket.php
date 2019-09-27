<?
namespace kDevelop\Ajax;

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
        global $USER;

        $arReturn = ["js_callback" => "addToBasketCallBack"];
        if (is_array($arParams["offer_id"]) && count($arParams["offer_id"]) > 0) {
            if (isset($arParams["price_id"]) && strlen($arParams["price_id"]) > 0) {
                if (\Bitrix\Main\Loader::includeModule("sale")) {
                    $arParams["qnt"] = intval($arParams["qnt"]);
                    //qnt
                    if ($arParams["qnt"] == 0) {
                        $arParams["qnt"] = count($arParams["offer_id"]);
                    }
                    //end
                    $rsItem = \CIBlockElement::GetList(
                        [],
                        [
                            "IBLOCK_ID" => IBLOCK_CATALOG_CATALOGSKU,
                            "ID" => $arParams["offer_id"],
                            //"!CATALOG_STORE_AMOUNT_".STORE_ID => false
                        ],
                        false,
                        false,
                        ["IBLOCK_ID", "ID", "NAME", "PREVIEW_PICTURE", "XML_ID", "CATALOG_GROUP_".$arParams["price_id"]]
                    );
                    if ($rsItem->SelectedRowsCount() == 0) {
                        $arReturn["msg"] = self::getMsg("ITEMS_NOT_AVAILABLE");
                    } else {
                        while ($arItem = $rsItem->fetch()) {
                            if ($arItem["CATALOG_AVAILABLE"] != "Y" || floatval($arItem["CATALOG_PRICE_".$arParams["price_id"]]) == 0) {
                                $arReturn["msg"] = self::getMsg("ITEMS_NOT_AVAILABLE", $arItem["NAME"]);
                                continue;
                            }

                            //general price info
                            $arPrice = \CCatalogProduct::GetOptimalPrice(
                                $arItem["ID"],
                                $arParams["qnt"],
                                $USER->GetUserGroupArray(),
                                "N",
                                [
                                    [
                                        "ID" => $arItem["CATALOG_PRICE_ID_".$arParams["price_id"]],
                                        "PRICE" => $arItem["CATALOG_PRICE_".$arParams["price_id"]],
                                        "CURRENCY" => "RUB",
                                        "CATALOG_GROUP_ID" => $arParams["price_id"]
                                    ]
                                ]
                            );
                            //end

                            if ($basketId = \CSaleBasket::Add([
                                "PRODUCT_ID" => $arItem["ID"],
                                "PRODUCT_PRICE_ID" => $arPrice["PRICE"]["ID"],
                                //"PRICE_TYPE_ID" => $arPrice["RESULT_PRICE"]["PRICE_TYPE_ID"],
                                "PRICE" => $arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"],
                                "BASE_PRICE" => $arPrice["RESULT_PRICE"]["BASE_PRICE"],
                                "CUSTOM_PRICE" => "Y",
                                "CURRENCY" => "RUB",
                                "WEIGHT" => $arItem["CATALOG_WEIGHT"],
                                "QUANTITY" => $arParams["qnt"],
                                "LID" => SITE_ID,
                                "DELAY" => "N",
                                "CAN_BUY" => "Y",
                                "NAME" => $arItem["NAME"],
                                "PRODUCT_XML_ID" => $arItem["XML_ID"],
                                "MODULE" => "catalog",
                                "NOTES" => "",
                                "PRODUCT_PROVIDER_CLASS" => "",
                                "IGNORE_CALLBACK_FUNC" => "Y",
                                "DISCOUNT_PRICE" => $arPrice["RESULT_PRICE"]["DISCOUNT"],
                                "DISCOUNT_NAME" => $arPrice["DISCOUNT"]["NAME"],
                                //"DISCOUNT_VALUE" => $arPrice["RESULT_PRICE"]["DISCOUNT"],
                                "DISCOUNT_COUPON" => "",
                                "PROPS" => []
                            ])) {
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
}