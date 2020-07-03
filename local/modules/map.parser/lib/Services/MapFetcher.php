<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Services;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CSaleLocation;
use kDevelop\MapParser\DTO\Map;
use kDevelop\MapParser\DTO\Point;
use kDevelop\MapParser\DTO\Polygon;
use kDevelop\MapParser\Repositories\MapRepository;
use kDevelop\MapParser\Repositories\PointRepository;
use kDevelop\MapParser\Repositories\PolygonRepository;

class MapFetcher
{
    /** @var MapRepository */
    private $mapRepository;

    /** @var PolygonRepository */
    private $polygonRepository;

    /** @var PointRepository */
    private $pointRepository;

    public function __construct()
    {
        $this->mapRepository = new MapRepository();
        $this->polygonRepository = new PolygonRepository();
        $this->pointRepository = new PointRepository();
    }

    /**
     * @param string $locationCode
     * @return Map|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function createByLocation(string $locationCode): ?Map
    {
        $mapSource = $this->mapRepository->getByLocation($locationCode);

        if (!$mapSource) {
            return null;
        }

        return $this->createMap($mapSource);
    }

    /**
     * @return array|Map[]
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function createAll(): array
    {
        $result = [];
        $mapSource = $this->mapRepository->all();

        foreach ($mapSource as $mapSourceItem) {
            $result[] = $this->createMap($mapSourceItem);
        }

        return $result;
    }

    /**
     * @param array $mapSource
     * @return Map
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function createMap(array $mapSource) : Map
    {
        $map = new Map();
        $map->setId((int) $mapSource['ID']);
        $map->setLocation($mapSource['LOCATION_CODE']);

        $polygonSource = $this->polygonRepository->getByMap(
            $map->getId()
        );
        $pointSource = $this->pointRepository->getByPolygonList(array_column($polygonSource, 'ID'));

        foreach ($polygonSource as $polygonItem) {
            $polygon = new Polygon((float) $polygonItem['PRICE']);
            $polygon->setId((int) $polygonItem['ID']);

            foreach ($pointSource as $pointItem) {
                if ($pointItem['POLYGON_ID'] !== $polygonItem['ID']) continue;

                $point = new Point((float) $pointItem['LAT'], (float) $pointItem['LONG']);
                $point->setId((int) $pointItem['ID']);

                $polygon->setPoint($point);
            }

            $map->setPolygon($polygon);
        }

        $location = CSaleLocation::GetList(
            [],
            ["CODE" => $map->getLocation()],
            false,
            false,
            ["ID", "CITY_NAME"]
        )->fetch();

        if ($location) {
            $map->setTitle((string) $location['CITY_NAME']);
        }

        return $map;
    }
}
