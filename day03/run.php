<?php

declare(strict_types=1);

$input = <<<EXMAPLE
00100
11110
10110
10111
10101
01111
00111
11100
10000
11001
00010
01010
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(static fn (string $line) => array_map('intval', str_split($line)), $input);

// Part 1

$bits = count($input[0]);
$count = count($input);
$mask = (1 << $bits) - 1;
$gamma = 0;
$epsilon = 0;

for ($i = 0; $i < $bits; $i++) {
    $nSum = array_sum(array_column($input, $i));
    $gamma += ($nSum / $count >= 0.5) << ($bits - $i - 1);
}

$epsilon = $gamma ^ $mask;

echo '[Part 1] Submarine power consumption: ', ($gamma * $epsilon), \PHP_EOL;
