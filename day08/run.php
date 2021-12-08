<?php

declare(strict_types=1);

$input = <<<EXMAPLE
be cfbegad cbdgef fgaecd cgeb fdcge agebfd fecdb fabcd edb | fdgacbe cefdb cefbgd gcbe
edbfga begcd cbg gc gcadebf fbgde acbgfd abcde gfcbed gfec | fcgedb cgb dgebacf gc
fgaebd cg bdaec gdafb agbcfd gdcbef bgcad gfac gcb cdgabef | cg cg fdcagb cbg
fbegcd cbd adcefb dageb afcb bc aefdc ecdab fgdeca fcdbega | efabcd cedba gadfec cb
aecbfdg fbg gf bafeg dbefa fcge gcbea fcaegb dgceab fcbdga | gecf egdcabf bgf bfgea
fgeab ca afcebg bdacfeg cfaedg gcfdb baec bfadeg bafgc acf | gebdcfa ecba ca fadegcb
dbcfg fgd bdegcaf fgec aegbdf ecdfab fbedc dacgb gdcebf gf | cefg dcbef fcge gbcadfe
bdfegc cbegaf gecbf dfcage bdacg ed bedf ced adcbefg gebcd | ed bcgafe cdgba cbgef
egadfb cdbfeg cegd fecab cgb gbdefca cg fgcdab egfdb bfceg | gbdfcae bgc cg cgb
gcafb gcf dcaebfg ecagb gf abcdeg gaef cafbge fdbac fegbdc | fgae cfgab fg bagce
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));
$input = array_map(
    static fn(string $line): array => array_chunk(preg_split('/( \| | )/', $line), 10),
    $input,
);

// Part 1

$segmentCount = [
    1 => 2,
    4 => 4,
    7 => 3,
    8 => 7,
];

$uniqueSegmentCount = array_flip($segmentCount);
$count = 0;

foreach ($input as [$patterns, $output]) {
    foreach (array_map('strlen', $output) as $len) {
        if (isset($uniqueSegmentCount[$len])) {
            $count++;
        }
    }
}

echo '[Part 1] number of appearances of digits 1, 4, 7 and 8: ', $count, \PHP_EOL;
