<?php

declare(strict_types=1);

$input = <<<EXMAPLE
0,9 -> 5,9
8,0 -> 0,8
9,4 -> 3,4
2,2 -> 2,1
7,0 -> 7,4
6,4 -> 2,0
0,9 -> 2,9
3,4 -> 1,4
0,0 -> 8,8
5,5 -> 8,2
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(
    static fn (string $line) => array_map(
        static fn (string $part) => array_map('intval', explode(',', $part)),
        explode(' -> ', $line),
    ),
    $input,
);

// Part 1

$map = [];

foreach ($input as [$a, $b]) {
    [$x1, $y1, $x2, $y2] = [...$a, ...$b];
    if ($x1 !== $x2 && $y1 !== $y2) {
        // only consider horizontal and vertical lines
        continue;
    }
//    echo sprintf('[%2d,%2d] -> [%2d, %2d]', $x1, $y1, $x2, $y2), \PHP_EOL;
    for ($x = $x1, $dX = ($x2 <=> $x1) ?: 1, $tX = $x2 + $dX; $x !== $tX; $x += $dX) {
        for ($y = $y1, $dY = ($y2 <=> $y1) ?: 1, $tY = $y2 + $dY; $y !== $tY; $y += $dY) {
//            echo '[',$x,',',$y,']', \PHP_EOL;
            $map[$y][$x] = ($map[$y][$x] ?? 0) + 1;
        }
    }
}

$overlaps = array_sum(array_map(static fn (array $row) => count(array_filter($row, static fn (int $value): bool => $value >= 2)), $map));

echo '[Part 1] Number of points where at least two lines overlap: ', $overlaps, \PHP_EOL;
