<?php

declare(strict_types=1);

namespace Maff\Aoc;

use Ds\Hashable;

class HashableVector2 extends Vector2 implements Hashable
{

    public function hash(): string
    {
        return $this->x . ',' . $this->y;
    }

    public function equals($obj): bool
    {
        if (!$obj instanceof self) {
            throw new \InvalidArgumentException(sprintf('Can only do equal check on object of type %s, got %s', self::class, $obj::class));
        }

        return $obj->x === $this->x && $obj->y === $this->y;
    }
}
