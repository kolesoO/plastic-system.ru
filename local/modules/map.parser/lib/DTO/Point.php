<?php

declare(strict_types=1);

namespace kDevelop\MapParser\DTO;

class Point
{
    /** @var float */
    private $lat;

    /** @var float */
    private $long;

    public function __construct(float $lat, float $long)
    {
        $this->lat = $lat;
        $this->long = $long;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLong(): float
    {
        return $this->long;
    }

    public function toArray(): array
    {
        return [
            $this->long,
            $this->lat,
        ];
    }
}
