<?php

namespace kDevelop\Service;

use \Bitrix\Main\Event;
use \Bitrix\Sale\Delivery\CalculationResult;
use \Bitrix\Sale\Shipment;
use \Bitrix\Sale\Order as OrderSale;

class Order
{
    /**
     * @var array
     */
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

        AddMessage2Log($arFields);
    }

    /**
     * @param Event $event
     * @return CalculationResult
     * @throws \Yandex\Geo\Exception
     * @throws \Yandex\Geo\Exception\CurlError
     * @throws \Yandex\Geo\Exception\ServerError
     */
    public static function onSaleDeliveryServiceCalculateHandler(Event $event)
    {
        $params = $event->getParameters();

        /** @var CalculationResult $result */
        $result = $params["RESULT"];

        $deliveryPrice = 0;
        $arPropId = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, "ORDER_PROP_") !== 0) continue;
            $arPropId[] = str_replace("ORDER_PROP_", "", $key);
        }
        if (count($arPropId) > 0) {
            if ($arProp = \CSaleOrderProps::GetList(
                [],
                ["ID" => $arPropId, "IS_ADDRESS" => "Y", "PERSON_TYPE_ID" => $_POST["PERSON_TYPE"]],
                false,
                false,
                ["ID", "NAME"]
            )->fetch()) {
                if (strlen($_POST["ORDER_PROP_" . $arProp["ID"]]) > 0) {
                    //
                    $obCache = new \CPHPCache();
                    if ($obCache->InitCache(
                        3600,
                        serialize(["LOCATION_NAME" => $_POST["ORDER_PROP_" . $arProp["ID"]]]),
                        "/iblock/locations_geo_data" //TODO: вынести в конфиг
                    )) {
                        extract($obCache->GetVars(), EXTR_OVERWRITE);
                    } elseif ($obCache->StartDataCache()) {
                        $yaApi = new \Yandex\Geo\Api();
                        $yaApi->setToken("5a8e55ae-66ea-4959-8e40-16dc606be5c9"); //TODO: вынести в конфиг
                        $response = $yaApi
                            ->setQuery($_POST["ORDER_PROP_" . $arProp["ID"]])
                            //->setLimit(1)
                            ->setLang(\Yandex\Geo\Api::LANG_RU)
                            ->load()
                            ->getResponse();
                        $pointList = $response->getList();
                        $obCache->EndDataCache([
                            "pointInfo" => isset($pointList[0]) ? $pointList[0]->getData() : null
                        ]);
                    }
                    if (isset($pointInfo)) {
                        $rsRegions = \CIBlockElement::GetList(
                            [],
                            [
                                "IBLOCK_ID" => IBLOCK_SERVICE_DELIVERY_PRICE,
                                "ACTIVE" => "Y"
                            ],
                            false,
                            false,
                            ["ID", "IBLOCK_ID"]
                        );
                        while ($item = $rsRegions->getNextElement()) {
                            $props = $item->getProperties();
                            if (!isset($props["polygon"]) || !isset($props["price"])) continue;
                            $polygonBox = [];
                            foreach ($props["polygon"]["VALUE"] as $coordsItem) {
                                $polygonBox[] = explode(",", $coordsItem);
                            }
                            if (count($polygonBox) > 0) {
                                $sbPolygonEngine = new SbPolyPointer($polygonBox);
                                if ($sbPolygonEngine->isCrossesWith($pointInfo["Latitude"], $pointInfo["Longitude"])) {
                                    $deliveryPrice = floatval($props["price"]["VALUE"]);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        $result->setDeliveryPrice($deliveryPrice);

        return $result;
    }

    /**
     * @param $orderID
     * @param $arFields
     */
    protected static function saleNewOrderHandler($orderID, &$arFields)
    {
        global $APPLICATION;

        //содержиое письма
        \ob_start();
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
                "SHOW_ORDER_PAYMENT" => "Y",
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
        $return = \ob_get_contents();
        \ob_end_clean();

        $arFields["ORDER_LIST"] = $return;
        //end

        //email склада
        $arFields['STORE_EMAIL'] = '';
        $order = OrderSale::load($orderID);
        $propertyCollection = $order->getPropertyCollection();
        $locPropValue = $propertyCollection->getDeliveryLocation();
        if ($arLocation = \CSaleLocation::GetList(
            [],
            ["CODE" => $locPropValue->getValue()],
            false,
            false,
            ["ID", "CITY_NAME"]
        )->fetch()) {
            $arProp["VALUE"] = $arLocation["CODE"];
            if ($arStore = \CCatalogStore::GetList(
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
