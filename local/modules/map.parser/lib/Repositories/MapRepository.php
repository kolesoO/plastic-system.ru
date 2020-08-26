<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Repositories;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DeleteResult;
use Bitrix\Main\SystemException;
use Exception;
use kDevelop\MapParser\DTO\Map;
use kDevelop\MapParser\Models\MapTable;

class MapRepository
{
    /**
     * @param Map $map
     * @return AddResult
     * @throws Exception
     */
    public function add(Map $map): AddResult
    {
        return MapTable::add([
            'LOCATION_CODE' => $map->getLocation(),
        ]);
    }

    /**
     * @param int $id
     * @return array|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function find(int $id): ?array
    {
        $result = MapTable::getById($id)->fetch();

        return $result !== false ? $result : null;
    }

    /**
     * @param string $locationCode
     * @return array|null
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getByLocation(string $locationCode): ?array
    {
        $result = MapTable::getList([
            'filter' => ['LOCATION_CODE' => $locationCode]
        ])->fetch();

        return $result !== false ? $result : null;
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function all(): array
    {
        return MapTable::getList([])->fetchAll();
    }

    /**
     * @param int $id
     * @return DeleteResult
     * @throws Exception
     */
    public function delete(int $id): DeleteResult
    {
        return MapTable::delete($id);
    }
}
