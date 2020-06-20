<?php

declare(strict_types=1);

namespace kDevelop\MapParser\DTO;

class Map
{
    /** @var Polygon[] */
    private $polygons;

    /** @var string */
    private $title;

    /** @var string */
    private $location;

    /**
     * @return Polygon[]
     */
    public function getPolygons(): array
    {
        return $this->polygons;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setPolygon(Polygon $polygon): self
    {
        $this->polygons[] = $polygon;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }
}
