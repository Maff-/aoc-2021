<?php

declare(strict_types=1);

$input = <<<EXAMPLE
start-A
start-b
A-c
A-b
b-d
A-end
b-end
EXAMPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(static fn(string $line): array => explode('-', $line), $input);

// Part 1

$connections = [];
foreach ($input as [$a, $b]) {
    $connections[$a] ??= [];
    $connections[$b] ??= [];
    if ($a !== 'end' && $b !== 'start') {
        $connections[$a][] = $b;
    }
    if ($b !== 'end' && $a !== 'start') {
        $connections[$b][] = $a;
    }
}

function walk(string $current, array $path, array &$paths): void
{
    global $connections;
    $options = array_values(array_filter(
        $connections[$current],
        static fn(string $option) => ctype_upper($option) || !in_array($option, $path, true)
    ));
    if (!$options) {
        if (end($path) === 'end') {
            $paths[] = $path;
        }
        return;
    }
    foreach ($options as $option) {
        walk($option, [...$path, $option], $paths);
    }
}

$paths = [];
walk('start', ['start'], $paths);

echo '[Part 1] paths through the cave system that visit small caves at most once: ', count($paths), \PHP_EOL;
