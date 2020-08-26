<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Models;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;

class MapTable extends DataManager
{
    /** @inheritDoc */
    public static function getTableName()
    {
        return 'kdevelop_maps';
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
            new StringField('LOCATION_CODE'),
        ];
    }
}
