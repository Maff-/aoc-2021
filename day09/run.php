<?php

declare(strict_types=1);

$input = <<<EXMAPLE
2199943210
3987894921
9856789892
8767896789
9899965678
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(static fn(array $row) => array_map('intval', $row), array_map('str_split', $input));

// Part 1

$height = count($input);
$width = count($input[0]);
const DIRECTIONS = [[-1, 0], [0, 1], [1, 0], [0, -1]];

$riskSum = 0;

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $depth = $input[$y][$x];
        $lowest = true;
        foreach (DIRECTIONS as [$dY, $dX]) {
            $adjacent = $input[$y + $dY][$x + $dX] ?? null;
            if ($adjacent !== null && $adjacent <= $depth) {
                $lowest = false;
                break;
            }
        }
        if ($lowest) {
            $riskSum += $depth + 1;
        }
    }
}

echo '[Part 1] sum of the risk levels of all low points: ', $riskSum, \PHP_EOL;

function mapBasins(int $x, int $y, array $map, array &$result, array &$sizes, int $n = 0): void
{
    $depth = $map[$y][$x] ?? null;
    if ($depth === null) {
        return;
    }
    if ($depth === 9) {
        $result[$y][$x] = false;
        return;
    }
    if (($result[$y][$x] ?? null) === null) {
        $result[$y][$x] = $n;
        $sizes[$n] ??= 0;
        $sizes[$n]++;
    }
    foreach (DIRECTIONS as [$dY, $dX]) {
        if (($result[$y + $dY][$x + $dX] ?? null) === null) {
            mapBasins($x + $dX, $y + $dY, $map, $result, $sizes, $n);
        }
    }
}

$basinMap = [];
$basinSizes = [];

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $basic = (array_key_last($basinSizes) ?? -1) + 1;
        mapBasins($x, $y, $input, $basinMap, $basinSizes, $basic);
    }
}

rsort($basinSizes);
$product = array_product(array_slice($basinSizes, 0, 3));

echo '[Part 2] product of the three largest basins sizes: ', $product, \PHP_EOL;
