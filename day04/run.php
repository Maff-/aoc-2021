<?php

declare(strict_types=1);

$input = <<<EXMAPLE
7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1

22 13 17 11  0
 8  2 23  4 24
21  9 14 16  7
 6 10  3 18  5
 1 12 20 15 19

 3 15  0  2 22
 9 18 13 17  5
19  8  7 25 23
20 11 10 24  4
14 21 16 12  6

14 21 17 24  4
10 16 15  9 19
18  8 23 26 20
22 11 13  6  5
 2  0 12  3  7
EXMAPLE;

$input = file_get_contents('input.txt');

$size = 5;

$input = explode("\n", trim($input));
$numbers = array_map('intval', explode(',', array_shift($input)));
$cards = array_map(static function (array $lines) use ($size) {
    return array_map(static fn (string $line) => array_map('intval', preg_split('/\s+/', trim($line))), array_slice($lines, 1));
}, array_chunk($input, $size + 1));
$cardsBackup = $cards;

// Part 1

function array_all_null(array $array): bool
{
    foreach ($array as $value) {
        if ($value !== null) {
            return false;
        }
    }
    return true;
}

$winningCard = null;
$lastNumber = null;

foreach ($numbers as $number) {
//    echo 'Number drawn: ', $number, PHP_EOL;
    $lastNumber = $number;
    foreach ($cards as $n => $card) {
//        echo 'Checking card #', $n, PHP_EOL;
        foreach ($card as $row => $values) {
            foreach ($values as $col => $value) {
                if ($number === $value) {
                    $cards[$n][$row][$col] = null;
//                    echo sprintf('Found %d at card #%d[%d][%d]', $number, $n, $row, $col), PHP_EOL;
                    break 2;
                }
            }
        }
        foreach ($cards[$n] as $values) {
            if (array_all_null($values)) {
                $winningCard = $n;
                break 3;
            }
        }
        $cols = array_map(null, ...$cards[$n]); // transpose matrix
        foreach ($cols as $values) {
            if (array_all_null($values)) {
                $winningCard = $n;
                break 3;
            }
        }
    }
}

if ($winningCard === null) {
    throw new \RuntimeException('Failed to find winning card');
}

$sumOfUnmarkedNumbers = array_sum(array_map('array_sum', $cards[$winningCard]));

echo '[Part 1] Final score: ', ($sumOfUnmarkedNumbers * $lastNumber), \PHP_EOL;

// Part 2

$cards = $cardsBackup;
$cardCount = count($cards);
$winningCards = [];
$lastNumber = null;

foreach ($numbers as $number) {
//    echo 'Number drawn: ', $number, PHP_EOL;
    $lastNumber = $number;
    foreach ($cards as $n => $card) {
        if (in_array($n, $winningCards, true)) {
            continue;
        }
//        echo 'Checking card #', $n, PHP_EOL;
        foreach ($card as $row => $values) {
            foreach ($values as $col => $value) {
                if ($number === $value) {
                    $cards[$n][$row][$col] = null;
                    break 2;
                }
            }
        }
        // check for bingo
        foreach ($cards[$n] as $row => $values) {
            if (array_all_null($values)) {
                $winningCard = $n;
                $winningCards[] = $n;
//                echo sprintf('Bingo at card #%d, row %d complete', $n, $row), PHP_EOL;
                continue 2;
            }
        }
        $cols = array_map(null, ...$cards[$n]); // transpose matrix
        foreach ($cols as $col => $values) {
            if (array_all_null($values)) {
                $winningCard = $n;
                $winningCards[] = $n;
//                echo sprintf('Bingo at card #%d, col %d complete', $n, $col), PHP_EOL;
                continue 2;
            }
        }
    }
    if (count($winningCards) === $cardCount) {
        break 1;
    }
}

if ($winningCard === null) {
    throw new \RuntimeException('Failed to find winning card');
}

$sumOfUnmarkedNumbers = array_sum(array_map('array_sum', $cards[$winningCard]));

echo '[Part 2] Final score (card #', $winningCard ,'): ', ($sumOfUnmarkedNumbers * $lastNumber), \PHP_EOL;
