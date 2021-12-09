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
$directions = [[-1, 0], [0, 1], [1, 0], [0, -1]];

$riskSum = 0;

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        $depth = $input[$y][$x];
        $lowest = true;
        foreach ($directions as [$dY, $dX]) {
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
