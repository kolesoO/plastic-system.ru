<?php

declare(strict_types=1);

namespace kDevelop\MapParser\Services;

use kDevelop\MapParser\DTO\Map;
use kDevelop\MapParser\DTO\Point;
use kDevelop\MapParser\DTO\Polygon;
use kDevelop\MapParser\Exceptions;
use Throwable;

class Parser
{
    /**
     * @param string $url
     * @return Map
     * @throws Exceptions\Parser
     */
    public function parseByUrl(string $url): Map
    {
        $result = new Map();
        $sourceData = file_get_contents($url);

        if (!is_string($sourceData)) {
            return $result;
        }

        preg_match_all('/<script type="application\/json" class="config-view">([^<]+)/s', $sourceData, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (!isset($match[1])) continue;

            try {
                $data = json_decode($match[1], true);
                $result->setTitle((string) $data['meta']['breadcrumbs'][3]['name']);

                foreach ($data['userMap']['features'] as $key => $sourceArea) {
                    if ($key == 0) continue;

                    $price = explode(' ', $sourceArea['title']);
                    $area = new Polygon((float) $price[0]);

                    foreach ($sourceArea['geometry']['coordinates'][0] as $pointSource) {
                        $area->setPoint(
                            new Point((float) $pointSource[0], (float) $pointSource[1])
                        );
                    }

                    $result->setPolygon($area);
                }
            } catch (Throwable $exception) {
                throw new Exceptions\Parser('Cannot parse map data');
            }
        }

        return $result;
    }
}
