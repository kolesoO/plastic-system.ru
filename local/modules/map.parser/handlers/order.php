<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Handlers;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Event;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Delivery\CalculationResult;
use CPHPCache;
use CSaleOrderProps;
use kDevelop\MapParser\DTO\Point;
use kDevelop\MapParser\Services\MapFetcher;
use Yandex\Geo\Api;
use Yandex\Geo\Exception;
use Yandex\Geo\Exception\CurlError;
use Yandex\Geo\Exception\ServerError;

class Order
{
    /**
     * @param Event $event
     * @return CalculationResult
     * @throws CurlError
     * @throws Exception
     * @throws ServerError
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
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
            if ($arProp = CSaleOrderProps::GetList(
                [],
                ["ID" => $arPropId, "IS_ADDRESS" => "Y", "PERSON_TYPE_ID" => $_POST["PERSON_TYPE"]],
                false,
                false,
                ["ID", "NAME"]
            )->fetch()) {
                if (strlen($_POST["ORDER_PROP_" . $arProp["ID"]]) > 0) {
                    //
                    $obCache = new CPHPCache();
                    if ($obCache->InitCache(
                        3600,
                        serialize(["LOCATION_NAME" => $_POST["ORDER_PROP_" . $arProp["ID"]]]),
                        "/iblock/locations_geo_data" //TODO: вынести в конфиг
                    )) {
                        extract($obCache->GetVars(), EXTR_OVERWRITE);
                    } elseif ($obCache->StartDataCache()) {
                        $yaApi = new Api();
                        $yaApi->setToken("5a8e55ae-66ea-4959-8e40-16dc606be5c9"); //TODO: вынести в конфиг
                        $response = $yaApi
                            ->setQuery($_POST["ORDER_PROP_" . $arProp["ID"]])
                            //->setLimit(1)
                            ->setLang(Api::LANG_RU)
                            ->load()
                            ->getResponse();
                        $pointList = $response->getList();
                        $obCache->EndDataCache([
                            "pointInfo" => isset($pointList[0]) ? $pointList[0]->getData() : null
                        ]);
                    }
                    if (isset($pointInfo)) {
                        $mapFetcher = new MapFetcher();
                        $maps = $mapFetcher->createAll();

                        foreach ($maps as $map) {
                            foreach ($map->getPolygons() as $polygon) {
                                if ($polygon->contains(
                                    (new Point($pointInfo["Longitude"], $pointInfo["Latitude"]))->toArray()
                                )) {
                                    $deliveryPrice = $polygon->getPrice();

                                    break 2;
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
}
