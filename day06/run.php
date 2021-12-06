<?php

declare(strict_types=1);

$input = <<<EXMAPLE
3,4,3,1,2
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode(',', trim($input));
$input = array_map('intval', $input);

// Part 1

$period = 7;
$init = 2;
$days = 80;

$state = $input;
for ($d = 0; $d < $days; $d++) {
    $count = count($state);
    for ($n = 0; $n < $count; $n++) {
        $timer = $state[$n];
        if ($timer === 0) {
            $state[$n] = $period - 1;
            $state[] = $init + $period - 1;
        } else {
            $state[$n]--;
        }
    }
}

echo '[Part 1] Number of lanternfish after ', $days, ' days: ', count($state), \PHP_EOL;

// Part 2 - a more efficient way

$days = 256;

$state = array_fill(0, $init + $period, 0);
$state = array_replace($state, array_count_values($input));
for ($d = 0; $d < $days; $d++) {
    $done = $state[0] ?? 0;
    for ($t = 1; $t < ($init + $period); $t++) {
        $state[$t - 1] = $state[$t];
    }
    $state[$period - 1] += $done;
    $state[$init + $period - 1] = $done;
}

echo '[Part 2] Number of lanternfish after ', $days, ' days: ', array_sum($state), \PHP_EOL;
