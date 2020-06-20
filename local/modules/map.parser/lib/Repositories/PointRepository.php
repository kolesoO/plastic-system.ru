<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Repositories;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\SystemException;
use Exception;
use kDevelop\MapParser\DTO\Point;
use kDevelop\MapParser\Models\PointTable;

class PointRepository
{
    /**
     * @param int $polygonId
     * @param Point $point
     * @return AddResult
     * @throws Exception
     */
    public function add(int $polygonId, Point $point): AddResult
    {
        return PointTable::add([
            'LAT' => $point->getLat(),
            'LONG' => $point->getLong(),
            'POLYGON_ID' => $polygonId,
        ]);
    }

    /**
     * @param array $polygonId
     * @return array|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getByPolygonList(array $polygonId): ?array
    {
        $result = PointTable::getList([
            'filter' => ['POLYGON_ID' => $polygonId],
        ])->fetchAll();

        return $result !== false ? $result : null;
    }
}
