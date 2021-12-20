<?php

declare(strict_types=1);

namespace Maff\Aoc;

class Vector2 implements \Stringable
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
    }

    public function manhattanDistance(self $other): int
    {
        return abs($other->x - $this->x) + abs($other->y - $this->y);
    }

    public function __toString(): string
    {
        return sprintf('[%d, %d]', $this->x, $this->y);
    }
}
