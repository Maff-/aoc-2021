<?php

declare(strict_types=1);

$input = <<<EXMAPLE
forward 5
down 5
forward 8
up 3
down 8
forward 2
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(static function (string $line): array {
    [$direction, $units] = explode(' ', $line);
    return [$direction, (int) $units];
}, $input);

// Part 1

$depth = 0;
$pos = 0;

foreach ($input as [$direction, $units]) {
    switch ($direction) {
        case 'up':
            $depth -= $units;
            break;
        case 'down':
            $depth += $units;
            break;
        case 'forward':
            $pos += $units;
            break;
        default:
            throw new \RuntimeException(sprintf('Invalid direction "%s"', $direction));
    }
}

echo '[Part 1] Final horizontal position times final depth: ', ($depth * $pos), \PHP_EOL;
