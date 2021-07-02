<?php

declare(strict_types=1);

namespace kDevelop\Service;

use CPHPCache;
use CSite;

class MultiSite
{
    private const CACHE_TIME = 3600;

    public static function valueOrDefault(?string $value, ?string $default): ?string
    {
        if (is_null($value)) {
            return $default;
        }

        return $value;
    }

    public static function getStringOptions(string $source): array
    {
        $result = [$source];

        foreach (self::getSiteList() as $site) {
            $result[] = $source . '_' . strtoupper($site['ID']);
        }

        return $result;
    }

    private static function getSiteList(array $filter = []): array
    {
        global $CACHE_MANAGER;

        $cache = new CPHPCache();
        $result = [];

        if ($cache->InitCache(self::CACHE_TIME, serialize($filter), "/sites")) {
            extract($cache->GetVars(), EXTR_OVERWRITE);

            return $result;
        }

        $cache->StartDataCache();
        $rsSites = CSite::GetList($by="sort", $order="desc", []);

        while ($site = $rsSites->fetch()) {
            $result[] = $site;
        }

        if(defined("BX_COMP_MANAGED_CACHE")) {
            $CACHE_MANAGER->StartTagCache("/sites");
            $CACHE_MANAGER->RegisterTag("sites");
            $CACHE_MANAGER->EndTagCache();
        }

        $cache->EndDataCache(["result" => $result]);

        return $result;
    }
}
