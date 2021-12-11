<?php

declare(strict_types=1);

$input = <<<EXMAPLE
5483143223
2745854711
5264556173
6141336146
6357385478
4167524645
2176841721
6882881134
4846848554
5283751526
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(static fn(array $row) => array_map('intval', $row), array_map('str_split', $input));

$height = count($input);
$width = count($input[0]);
const DIRECTIONS = [[-1, 0], [-1, 1], [0, 1], [1, 1], [1, 0], [1, -1], [0, -1], [-1, -1]];

// Part 1

$steps = 100;
$totalFlashes = 0;
$data = $input;

for ($step = 1; $step <= $steps; $step++) {
    // First, the energy level of each octopus increases by 1.
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $data[$y][$x]++;
        }
    }

    // cascade flashing
    $flashes = 0;
    $flashMap = array_fill(0, $height, array_fill(0, $width, false));
    while (true) {
        $flashed = false;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($data[$y][$x] > 9 && !$flashMap[$y][$x]) {
                    $flashed = true;
                    $flashMap[$y][$x] = true;
                    foreach (DIRECTIONS as [$dY, $dX]) {
                        [$aY, $aX] = [$y + $dY, $x + $dX];
                        if ($aY < 0 || $aY >= $height || $aX < 0 || $aX >= $width) {
                            continue;
                        }
                        $data[$aY][$aX]++;
                    }
                }
            }
        }
        if (!$flashed) {
            break;
        }
    }

    // any octopus that flashed during this step has its energy level set to 0
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($data[$y][$x] > 9) {
                $flashes++;
                $data[$y][$x] = 0;
            }
        }
    }

    $totalFlashes += $flashes;
}

echo '[Part 1] total flashes after ', $steps ,' steps: ', $totalFlashes, \PHP_EOL;

// Part 2

$data = $input;
$step = 1;
$octopuses = $width * $height;

while (true) {
    // First, the energy level of each octopus increases by 1.
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            $data[$y][$x]++;
        }
    }

    // cascade flashing
    $flashes = 0;
    $flashMap = array_fill(0, $height, array_fill(0, $width, false));
    while (true) {
        $flashed = false;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($data[$y][$x] > 9 && !$flashMap[$y][$x]) {
                    $flashed = true;
                    $flashMap[$y][$x] = true;
                    foreach (DIRECTIONS as [$dY, $dX]) {
                        [$aY, $aX] = [$y + $dY, $x + $dX];
                        if ($aY < 0 || $aY >= $height || $aX < 0 || $aX >= $width) {
                            continue;
                        }
                        $data[$aY][$aX]++;
                    }
                }
            }
        }
        if (!$flashed) {
            break;
        }
    }

    // any octopus that flashed during this step has its energy level set to 0
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($data[$y][$x] > 9) {
                $flashes++;
                $data[$y][$x] = 0;
            }
        }
    }

    if ($flashes === $octopuses) {
        break;
    }

    $step++;
}

echo '[Part 2] first step during which all octopuses flash: ', $step, \PHP_EOL;
