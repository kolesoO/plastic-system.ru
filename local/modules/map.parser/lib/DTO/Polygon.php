<?php

declare(strict_types=1);

namespace kDevelop\MapParser\DTO;

class Polygon
{
    /** @var int */
    private $id;

    /** @var Point[] */
    private $points;

    /** @var float */
    private $price;

    public function __construct(float $price)
    {
        $this->price = $price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Point[]
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setPoint(Point $point): self
    {
        $this->points[] = $point;

        return $this;
    }

    public function contains(array $point): bool
    {
        $polygon = $this->toArray();

        if($polygon[0] !== $polygon[count($polygon)-1]) {
            $polygon[count($polygon)] = $polygon[0];
        }

        $j = 0;
        $oddNodes = false;
        $x = $point[1];
        $y = $point[0];
        $n = count($polygon);

        for ($i = 0; $i < $n; $i++)
        {
            $j++;
            if ($j == $n)
            {
                $j = 0;
            }
            if ((($polygon[$i][0] < $y) && ($polygon[$j][0] >= $y)) || (($polygon[$j][0] < $y) && ($polygon[$i][0] >=
                        $y)))
            {
                if ($polygon[$i][1] + ($y - $polygon[$i][0]) / ($polygon[$j][0] - $polygon[$i][0]) * ($polygon[$j][1] -
                        $polygon[$i][1]) < $x)
                {
                    $oddNodes = !$oddNodes;
                }
            }
        }

        return $oddNodes;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->points as $point) {
            $result[] = $point->toArray();
        }

        return $result;
    }
}
