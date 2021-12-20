<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Maff\Aoc\HashableVector2;
use Maff\Aoc\Vector2;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

$count = $_SERVER['argv'][1] ?? 200_000;
$multiplier = 10 ** ((int)log10($count - 1) + 1);

$stopwatch = new Stopwatch();
$mem = [];

//$stopwatch->openSection('prep');
$stopwatch->openSection();

$stopwatch->start('coord_generation');
$coords = [];
for ($n = 0; $n < $count; $n++) {
    $x = random_int(0, $count - 1);
    $y = random_int(0, $count - 1);
    $coords[] = [$x, $y];
}
$stopwatch->stop('coord_generation');

$stopwatch->start('prio_generation');
$priorities = [];
for ($n = 0; $n < $count; $n++) {
    $priorities[] = random_int(0, $count - 1);
}
$stopwatch->stop('prio_generation');

//$uniqueCoordsCount = count(array_unique($coords, \SORT_REGULAR));

$array = [];
$stopwatch->start('fill_array_2d');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $array[$y][$x] = true;
}
$mem['fill_array_2d'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_array_2d');

$array1d = [];
$stopwatch->start('fill_array_1d');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $key = ($y * $multiplier) + $x;
    $array1d[$key] = true;
}
$mem['fill_array_1d'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_array_1d');
//assert(count($array1d) === $uniqueCoordsCount, sprintf('Expected 1d array to have %d elements, got %d', $uniqueCoordsCount, count($array1d)));

$arrayList = [];
$stopwatch->start('fill_array_list');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $arrayList[] = [$x, $y];
}
$mem['fill_array_list'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_array_list');

$array3d = [];
$stopwatch->start('fill_array_3d');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $array3d[$y][$x] = [0, 1, 2, 3];
}
$mem['fill_array_3d'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_array_3d');

$array2d0 = [];
$array2d1 = [];
$array2d3 = [];
$array2d4 = [];
$stopwatch->start('fill_array_4x2d');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $array2d0[$y][$x] = 0;
    $array2d1[$y][$x] = 1;
    $array2d2[$y][$x] = 2;
    $array2d3[$y][$x] = 3;
}
$mem['fill_array_4x2d'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_array_4x2d');

$array1d0 = [];
$array1d1 = [];
$array1d3 = [];
$array1d4 = [];
$stopwatch->start('fill_array_4x1d');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $key = ($y * $multiplier) + $x;
    $array1d0[$key] = 0;
    $array1d1[$key] = 1;
    $array1d2[$key] = 2;
    $array1d3[$key] = 3;
}
$mem['fill_array_4x1d'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_array_4x1d');

class FourPropObj {
    public function __construct(
        public int $a,
        public int $b,
        public int $c,
        public int $d,
    ) {
    }
}

$array = [];
$stopwatch->start('fill_array_2d_obj');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $array[$y][$x] = new FourPropObj(0, 1, 2, 3);
}
$mem['fill_array_2d_obj'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_array_2d_obj');

$objects = new \SplObjectStorage();
$stopwatch->start('fill_obj_storage');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $objects[new Vector2($x, $y)] = true;
}
$mem['fill_obj_storage'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_obj_storage');

$vector = new \Ds\Vector();
$stopwatch->start('fill_ds_vector');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $vector->push(new HashableVector2($x, $y));
}
$mem['fill_ds_vector'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_ds_vector');

$map = new \Ds\Map();
$stopwatch->start('fill_ds_map');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $map[new HashableVector2($x, $y)] = true;
}
$mem['fill_ds_map'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_ds_map');

$set = new \Ds\Set();
$stopwatch->start('fill_ds_set');
$memBefore = memory_get_usage(true);
foreach ($coords as [$x, $y]) {
    $set->add(new HashableVector2($x, $y));
}
$mem['fill_ds_set'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_ds_set');

$queue = new \Ds\PriorityQueue();
$stopwatch->start('fill_ds_prio_queue');
$memBefore = memory_get_usage(true);
foreach ($coords as $n => [$x, $y]) {
    $queue->push(new Vector2($x, $y), $priorities[$n]);
}
$mem['fill_ds_prio_queue'] = memory_get_usage(true) - $memBefore;
$stopwatch->stop('fill_ds_prio_queue');

$stopwatch->start('shuffle_coords');
shuffle($coords);
$stopwatch->stop('shuffle_coords');

$stopwatch->stopSection('prep');

$stopwatch->openSection();

$stopwatch->start('lookup_array_2d');
foreach ($coords as [$x, $y]) {
    $value = $array[$y][$x];
}
$stopwatch->stop('lookup_array_2d');

$stopwatch->start('lookup_array_1d');
foreach ($coords as [$x, $y]) {
    $key = ($y * $multiplier) + $x;
    $value = $array1d[$key];
}
$stopwatch->stop('lookup_array_1d');

if ($count >= 2_000) {
    echo "\e[31mSkipping lookup_array_list; number of elements is too large.\e[0m\n\n";
} else {
    $stopwatch->start('lookup_array_list');
    foreach ($coords as [$x, $y]) {
        $hit = in_array([$x, $y], $arrayList, true);
    }
    $stopwatch->stop('lookup_array_list');
}

//$stopwatch->start('lookup_obj_storage');
//foreach ($coords as [$x, $y]) {
//    $value = $objects[new Vector2($x, $y)];
//}
//$stopwatch->stop('lookup_obj_storage');

$stopwatch->start('lookup_ds_map');
foreach ($coords as [$x, $y]) {
    $value = $map[new HashableVector2($x, $y)];
}
$stopwatch->stop('lookup_ds_map');

$stopwatch->start('lookup_ds_set');
foreach ($coords as [$x, $y]) {
    $value = $set->contains(new HashableVector2($x, $y));
}
$stopwatch->stop('lookup_ds_set');

$stopwatch->start('lookup_ds_prio_queue');
foreach ($queue as $coord) {
    // no-op
}
$stopwatch->stop('lookup_ds_prio_queue');

$arraySorted = $arrayList;
$stopwatch->start('usort_array_list');
uksort($arraySorted, static fn(int $a, int $b): int => $priorities[$a] <=> $priorities[$b]);
$stopwatch->stop('usort_array_list');



$stopwatch->stopSection('lookup');

$stopwatch->openSection();




foreach ($stopwatch->getSections() as $section) {
    printf("\e[33m[%s]\e[0m\n", $section->getId() ?: '?');
    $events = $section->getEvents();
    usort($events, static fn(StopwatchEvent $a, StopwatchEvent $b): int => $a->getName() === '__section__' ? 1 : 0);
    foreach ($events as $event) {
        printf(
            "%-22s %4dms %6.1fMB%s\n",
            $event->getName() . ':',
            $event->getDuration(),
            $event->getMemory() / 1024 / 1024,
            isset($mem[$event->getName()]) ? sprintf(' %+6.1fMB', $mem[$event->getName()] / 1024 / 1024) : null,
        );
    }
    echo \PHP_EOL;
}

printf("Array: %0.1fMB\n", $mem['fill_array_2d'] / 1024 / 1024);
printf("1D Array: %0.1fMB\n", $mem['fill_array_1d'] / 1024 / 1024);
printf("SplObjectStorage with Vector2: %0.1fMB\n", $mem['fill_obj_storage'] / 1024 / 1024);
printf("Ds\Map with Vector2: %0.1fMB\n", $mem['fill_ds_map'] / 1024 / 1024);
printf("Ds\Set with Vector2: %0.1fMB\n", $mem['fill_ds_set'] / 1024 / 1024);

