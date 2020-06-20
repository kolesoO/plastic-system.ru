<?
declare(strict_types=1);

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use kDevelop\MapParser\DTO\Point;
use kDevelop\MapParser\Services\MapFetcher;
use Yandex\Geo\Api;
use Yandex\Geo\Exception\CurlError;
use Yandex\Geo\Exception\ServerError;

class CatalogDelivery extends CBitrixComponent
{
    /**
     * @param $params
     * @return array
     */
    public function onPrepareComponentParams($params)
    {
        $params["SORT_BY"] = isset($params["SORT_BY"]) ? $params["SORT_BY"] : "ID";
        $params["SORT_ORDER"] = isset($params["SORT_ORDER"]) ? $params["SORT_ORDER"] : "ASC";
        $params["CACHE_TIME"] = isset($params["CACHE_TIME"]) ? $params["CACHE_TIME"] : 36000000;

        return $params;
    }

    /**
     * @return mixed|void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws \Yandex\Geo\Exception
     * @throws CurlError
     * @throws ServerError
     */
    public function executeComponent()
    {
        if (!Loader::includeModule("sale")) return;

        if (!Loader::includeModule("map.parser")) return;

        //prepare filter
        $filter = [];
        if (isset($this->arParams["LOCATION_NAME"])) {
            $filter = ['NAME.NAME_UPPER' => $this->arParams["LOCATION_NAME"]];
        }
        //end

        if (count($filter) > 0) {
            $arLocation = LocationTable::getList([
                'select' => array("*", "NAME_RU" => "NAME.NAME"),
                'filter' => $filter,
            ])
                ->fetch();

            $this->arResult["LOCATION"] = $arLocation;

            $obCache = new CPHPCache();
            if ($obCache->InitCache(
                3600,
                serialize(["LOCATION_NAME" => $this->arParams['ADDRESS']]),
                "/iblock/locations_geo_data" //TODO: вынести в конфиг
            )) {
                extract($obCache->GetVars(), EXTR_OVERWRITE);
            } elseif ($obCache->StartDataCache()) {
                $yaApi = new Api();
                $yaApi->setToken("5a8e55ae-66ea-4959-8e40-16dc606be5c9"); //TODO: вынести в конфиг
                $response = $yaApi
                    ->setQuery($this->arParams['ADDRESS'])
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
                            $this->arResult['DELIVERY'] = [
                                'PRICE' => SaleFormatCurrency($polygon->getPrice(), 'RUB'),
                                'POLYGON' => $polygon,
                            ];

                            break 2;
                        }
                    }
                }
            }
        }

        $this->includeComponentTemplate();
    }
}
