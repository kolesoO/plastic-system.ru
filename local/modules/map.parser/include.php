<?php
use Bitrix\Main\Loader;

$config = include dirname(__FILE__) ."/config.php";

Loader::registerAutoLoadClasses(
    $config['MODULE_ID'],
	[
        '\kDevelop\MapParser\DTO\Point' => 'lib/DTO/Point.php',
        '\kDevelop\MapParser\DTO\Polygon' => 'lib/DTO/Polygon.php',
        '\kDevelop\MapParser\DTO\Map' => 'lib/DTO/Map.php',

        '\kDevelop\MapParser\Models\MapTable' => 'lib/Models/MapTable.php',
        '\kDevelop\MapParser\Models\PointTable' => 'lib/Models/PointTable.php',
        '\kDevelop\MapParser\Models\PolygonTable' => 'lib/Models/PolygonTable.php',

        '\kDevelop\MapParser\Repositories\MapRepository' => 'lib/Repositories/MapRepository.php',
        '\kDevelop\MapParser\Repositories\PointRepository' => 'lib/Repositories/PointRepository.php',
        '\kDevelop\MapParser\Repositories\PolygonRepository' => 'lib/Repositories/PolygonRepository.php',

        '\kDevelop\MapParser\Services\Parser' => 'lib/Services/Parser.php',
        '\kDevelop\MapParser\Services\MapFetcher' => 'lib/Services/MapFetcher.php',

        '\kDevelop\MapParser\Handlers\Order' => 'handlers/order.php',
    ]
);
