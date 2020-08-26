<?php

declare(strict_types=1);

namespace kDevelop\MapParser\DTO;

class Point
{
    /** @var int */
    private $id;

    /** @var float */
    private $lat;

    /** @var float */
    private $long;

    public function __construct(float $lat, float $long)
    {
        $this->lat = $lat;
        $this->long = $long;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLong(): float
    {
        return $this->long;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function toArray(): array
    {
        return [
            $this->long,
            $this->lat,
        ];
    }
}
