<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Repositories;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DeleteResult;
use Bitrix\Main\SystemException;
use Exception;
use kDevelop\MapParser\DTO\Polygon;
use kDevelop\MapParser\Models\PolygonTable;

class PolygonRepository
{
    /**
     * @param int $mapId
     * @param Polygon $polygon
     * @return AddResult
     * @throws Exception
     */
    public function add(int $mapId, Polygon $polygon): AddResult
    {
        return PolygonTable::add([
            'PRICE' => $polygon->getPrice(),
            'MAP_ID' => $mapId,
        ]);
    }

    /**
     * @param int $mapId
     * @return array|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getByMap(int $mapId): ?array
    {
        $result = PolygonTable::getList([
            'filter' => ['MAP_ID' => $mapId]
        ])->fetchAll();

        return $result !== false ? $result : null;
    }

    /**
     * @param int $id
     * @return DeleteResult
     * @throws Exception
     */
    public function delete(int $id): DeleteResult
    {
        return PolygonTable::delete($id);
    }
}
