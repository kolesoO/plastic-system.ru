<?php

declare(strict_types=1);

namespace kDevelop\MetaTemplates;

use Bitrix\Iblock\Template\Entity\Base;
use Bitrix\Iblock\Template\Functions\FunctionBase;
use Bitrix\Iblock\Template\NodeBase;
use Bitrix\Main\EventResult;
use CCatalogStore;

class CurrentStoreTemplate extends FunctionBase
{
    private const TEMPLATE = 'current_store';

    /** @var array|null */
    private static $cache = null;

    public static function calculateForce(string $value)
    {
        if (!preg_match('/\{' . self::TEMPLATE . '\s([a-zA-Z0-9_]+)\}/', $value, $matches)) {
            return $value;
        }

        if (!$matches[1]) {
            return $value;
        }

        $instance = new self();

        return str_replace(
            $matches[0],
            $instance->calculate([$matches[1]]),
            $value
        );
    }

    /**
     * @return EventResult|void
     */
    public static function eventHandler($event)
    {
        $parameters = $event->getParameters();
        $functionClass = $parameters[0];

        if ($functionClass === self::TEMPLATE) {
            return new EventResult(EventResult::SUCCESS, self::class);
        }
    }

    public function onPrepareParameters(Base $entity, $parameters)
    {
        return array_map(
            static function (NodeBase $parameter) use ($entity) {
                return $parameter->process($entity);
            },
            $parameters
        );
    }

    public function calculate(array $parameters)
    {
        if (!self::$cache) {
            self::$cache = CCatalogStore::GetList(
                [],
                array("ID" => STORE_ID),
                false,
                false,
                [
                    "ID",
                    "TITLE",
                    "ADDRESS",
                    "DESCRIPTION",
                    "UF_*"
                ]
            )->fetch();
        }

        $property = strtoupper($parameters[0] ?? 'TITLE');

        return self::$cache[$property] ?? '';
    }
}
