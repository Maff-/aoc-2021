<?php

declare(strict_types=1);

$input = <<<EXMAPLE
199
200
208
210
200
207
240
269
260
263
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map('intval', $input);

// Part 1

$increased = 0;
$prev = null;

foreach ($input as $depth) {
    if ($prev !== null && $depth > $prev) {
        $increased++;
    }
    $prev = $depth;
}

echo '[Part 1] Measurements that are larger than the previous measurement: ', $increased, \PHP_EOL;

// Part 2

$width = 3;
$count = count($input);
$last = $count - $width;
$increased = 0;
$prev = null;

for ($i = 0; $i <= $last; $i++) {
    $sum = array_sum(array_slice($input, $i, $width));
    if ($prev !== null && $sum > $prev) {
        $increased++;
    }
    $prev = $sum;
}

echo '[Part 2] Sums that are larger than the previous sums: ', $increased, \PHP_EOL;
