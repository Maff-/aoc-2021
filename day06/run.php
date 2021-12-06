<?php

declare(strict_types=1);

$input = <<<EXMAPLE
3,4,3,1,2
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode(",", trim($input));
$input = array_map('intval', $input);

// Part 1

$foo = 7;
$bar = 2;
$days = 80;

$state = $input;
for ($d = 0; $d < $days; $d++) {
    $count = count($state);
    for ($n = 0; $n < $count; $n++) {
        $timer = $state[$n];
        if ($timer === 0) {
            $state[$n] = $foo - 1;
            $state[] = $bar + $foo - 1;
        } else {
            $state[$n]--;
        }
    }
}

echo '[Part 1] Number of lanternfish after 80 days: ', count($state), \PHP_EOL;
