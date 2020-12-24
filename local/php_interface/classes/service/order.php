<?php

namespace kDevelop\Service;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\NotImplementedException;
use \Bitrix\Sale\Order as OrderSale;
use CCatalogStore;
use CSaleLocation;

class Order
{
    /** @var array */
    private static $methodMap = [
        "SALE_NEW_ORDER" => "saleNewOrderHandler"
    ];

    /**
     * @param $orderID
     * @param $eventName
     * @param $arFields
     */
    public static function OnOrderNewSendEmailHandler($orderID, &$eventName, &$arFields)
    {
        forward_static_call_array([self::class, self::$methodMap[$eventName]], [$orderID, &$arFields]);
    }

    /**
     * @param $orderID
     * @param $arFields
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws NotImplementedException
     */
    protected static function saleNewOrderHandler($orderID, &$arFields)
    {
        global $APPLICATION;

        //содержиое письма
        ob_start();
        $APPLICATION->IncludeComponent(
            "bitrix:sale.personal.order.detail.mail",
            "",
            [
                "ID" => $orderID,
                "SHOW_ORDER_BASKET" => "Y",
                "SHOW_ORDER_BASE" => "Y",
                "SHOW_ORDER_USER" => "Y",
                "SHOW_ORDER_PARAMS" => "Y",
                "SHOW_ORDER_BUYER" => "Y",
                "SHOW_ORDER_DELIVERY" => "Y",
                "SHOW_ORDER_PAYMENT" => "N",
                "SHOW_ORDER_SUM" => "Y",
                "CUSTOM_SELECT_PROPS" => array("NAME", "DISCOUNT_PRICE_PERCENT_FORMATED", "PRICE_FORMATED", "QUANTITY"),
                "PROP_1" => array(),
                "PROP_2" => array(),
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "PICTURE_WIDTH" => "110",
                "PICTURE_HEIGHT" => "110",
                "PICTURE_RESAMPLE_TYPE" => "1",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "PATH_TO_LIST" => "",
                "PATH_TO_CANCEL" => "",
                "PATH_TO_PAYMENT" => "",
                "DISALLOW_CANCEL" => "Y"
            ]
        );
        $return = ob_get_contents();
        ob_end_clean();

        $arFields["ORDER_LIST"] = $return;
        //end

        //email склада
        $arFields['STORE_EMAIL'] = '';
        $order = OrderSale::load($orderID);
        $propertyCollection = $order->getPropertyCollection();
        $locPropValue = $propertyCollection->getDeliveryLocation();
        if ($arLocation = CSaleLocation::GetList(
            [],
            ["CODE" => $locPropValue->getValue()],
            false,
            false,
            ["ID", "CITY_NAME"]
        )->fetch()) {
            $arProp["VALUE"] = $arLocation["CODE"];
            if ($arStore = CCatalogStore::GetList(
                ["SORT" => "ASC"],
                [
                    "ACTIVE"=>"Y",
                    "UF_CITY_NAME" => $arLocation['CITY_NAME']
                ],
                false,
                false,
                ["ID", "EMAIL"]
            )->fetch()) {
                if ($arStore['EMAIL'] != $arFields['SALE_EMAIL']) {
                    $arFields['STORE_EMAIL'] = $arStore['EMAIL'];
                }
            }
        }
        //end
    }
}
