<?php

declare(strict_types=1);

$input = <<<EXAMPLE
NNCB

CH -> B
HH -> N
CB -> H
NH -> C
HB -> C
HC -> B
HN -> C
NN -> C
BH -> H
NC -> B
NB -> B
BN -> B
BB -> N
BC -> B
CC -> N
CN -> C
EXAMPLE;

$input = file_get_contents('input.txt');

[$template, $pairs] = explode("\n\n", trim($input));
$pairs = array_map(
    static fn(string $line): array => explode(' -> ', $line),
    explode("\n", $pairs)
);
$pairs = array_combine(array_column($pairs, 0), array_column($pairs, 1));


// Part 1

$steps = 10;
$polymer = $template;


for ($step = 1; $step <= $steps; $step++) {
    $tmp = '';
    for ($i = 0, $max = strlen($polymer) - 1; $i < $max; $i++) {
        $tmp .= $polymer[$i] . $pairs[$polymer[$i] . $polymer[$i + 1]];
    }
    $tmp .= $polymer[$max];
    $polymer = $tmp;
}

$elements = str_split($polymer);
$elementCount = array_flip(array_count_values($elements));
ksort($elementCount);

$result = array_key_last($elementCount) - array_key_first($elementCount);

echo '[Part 1] quantity of the most common element minus the quantity of the least common element: ', $result, \PHP_EOL;

// Part 2 - a more efficient way

$steps = 40;
$polymer = $template;

$elementCount = array_count_values(str_split($polymer));
$pairCount = [];
for ($i = 0, $max = strlen($polymer) - 1; $i < $max; $i++) {
    $pair = $polymer[$i] . $polymer[$i + 1];
    $pairCount[$pair] ??= 0;
    $pairCount[$pair]++;
}

for ($step = 1; $step <= $steps; $step++) {
    $tmp = [];
    foreach ($pairCount as $pair => $count) {
        if (!$count) {
            continue;
        }
        $insertion = $pairs[$pair];
        $elementCount[$insertion] ??= 0;
        $elementCount[$insertion] += $count;

        $pairA = $pair[0] . $insertion;
        $pairB = $insertion . $pair[1];
        $tmp[$pairA] ??= 0;
        $tmp[$pairA] += $count;
        $tmp[$pairB] ??= 0;
        $tmp[$pairB] += $count;
    }
    $pairCount = $tmp;
}

$elementCount = array_flip($elementCount);
ksort($elementCount);
$result = array_key_last($elementCount) - array_key_first($elementCount);

echo '[Part 2] quantity of the most common element minus the quantity of the least common element: ', $result, \PHP_EOL;
