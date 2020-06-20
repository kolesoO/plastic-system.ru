<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Models;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\SystemException;

class PolygonTable extends DataManager
{
    /** @inheritDoc */
    public static function getTableName()
    {
        return 'kdevelop_polygons';
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new FloatField('PRICE'),
            new IntegerField('MAP_ID'),
        ];
    }
}
