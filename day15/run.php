<?php

declare(strict_types=1);

$input = <<<EXAMPLE
1163751742
1381373672
2136511328
3694931569
7463417111
1319128137
1359912421
3125421639
1293138521
2311944581
EXAMPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(static fn(array $row) => array_map('intval', $row), array_map('str_split', $input));

// Part 1, A* approach

const DIRECTIONS = [[0, 1], [1, 0], [0, -1], [-1, 0]]; // r, d, l, u
const Y = 0;
const X = 1;
const COST = 0;
const HEUR = 1;
const PREV = 2;
const DIST = 3;

$height = count($input);
$width = count($input[0]);
$start = [0, 0];
$end = [$height - 1, $width - 1];

$data = [];
foreach ($input as $y => $row) {
    foreach ($row as $x => $risk) {
        // cost, heuristic, via, distance
        $data[$y][$x] = [null, null, null, (int)floor(sqrt((($width - $x) ** 2) + (($height - $y) ** 2)))];
    }
}
$data[$start[Y]][$start[X]][COST] = 0;
$data[$start[Y]][$start[X]][COST] = 0;

$queue = [$start];
$done = [];

while (count($queue)) {
    [$y, $x] = $current = array_shift($queue);
    $currentData = $data[$y][$x];
    if ($current === $end) {
        break;
    }

    foreach (DIRECTIONS as [$dY, $dX]) {
        [$aY, $aX] = [$y + $dY, $x + $dX];
        if ($aY < 0 || $aY >= $height || $aX < 0 || $aX >= $width || ($done[$aY][$aX] ?? null)) {
            continue;
        }

        $risk = $input[$aY][$aX];
        $aCost = $currentData[COST] + $risk;
        $aHeur = $aCost + $data[$aY][$aX][DIST];
        $aData = $data[$aY][$aX];
        if ($aData[COST] === null || $aCost < $data[$aY][$aX][COST]) {
            $data[$aY][$aX][COST] = $aCost;
            $data[$aY][$aX][HEUR] = $aHeur;
            $data[$aY][$aX][PREV] = $current;
            $queue[] = [$aY, $aX];
        }
    }
    usort($queue, static fn($a, $b) => $data[$a[Y]][$a[X]][HEUR] <=> $data[$b[Y]][$b[X]][HEUR]);
    $done[$y][$x] = true;
}

if (!($data[$end[Y]][$end[X]] ?? null)) {
    throw new \RuntimeException('End not reached?');
}

$path = [];
$pathNode = $end;
while ($pathNode && $pathNode !== $start) {
    $path[] = $pathNode;
    $pathNode = $data[$pathNode[Y]][$pathNode[X]][PREV];
}
$path = array_reverse($path);
$pathRisk = array_map(static fn(array $node): int => $input[$node[Y]][$node[X]], $path);
$riskSum = array_sum($pathRisk);

echo '[Part 1] lowest total risk: ', $riskSum, \PHP_EOL;
