<?php

declare(strict_types=1);

$input = <<<EXMAPLE
16,1,2,0,4,2,7,1,2,14
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode(',', trim($input));
$input = array_map('intval', $input);

// Part 1

$max = max($input);
$min = min($input);
$result = [];
for ($pos = $min; $pos <= $max; $pos++) {
    $fuel = 0;
    foreach ($input as $crabPos) {
        $fuel += abs($pos - $crabPos);
    }
    $result[$pos] = $fuel;
}

$minFuel = min($result);

echo '[Part 1] Fuel spent aligning: ', $minFuel, \PHP_EOL;
