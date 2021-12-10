<?php

declare(strict_types=1);

$input = <<<EXMAPLE
[({(<(())[]>[[{[]{<()<>>
[(()[<>])]({[<{<<[]>>(
{([(<{}[<>[]}>{[]{[(<()>
(((({<>}<{<{<>}{[]{[]{}
[[<[([]))<([[{}[[()]]]
[{[{({}]{}}([{[{{{}}([]
{<[[]]>}<{[{[{[]{()[[[]
[<(<(<(<{}))><([]([]()
<{([([[(<>()){}]>(<<{{
<{([{{}}[<[[[<>{}]]]>[]]
EXMAPLE;

$input = file_get_contents('input.txt');

$input = explode("\n", trim($input));

const PAIRS = [
    '(' => ')',
    '[' => ']',
    '{' => '}',
    '<' => '>',
];

// Part 1

$scoreLookup = [
    ')' => 3,
    ']' => 57,
    '}' => 1197,
    '>' => 25137,
];

class ParserException extends \RuntimeException
{
    public function __construct(readonly public string $expected, readonly public ?string $found, readonly public ?int $pos)
    {
        parent::__construct(sprintf('Expected %s, but found %s instead at pos %d', $expected, $found, $pos));
    }
}

function parse(string $code, int $pos, string $until): ?int
{
    for ($len = strlen($code); $pos < $len; $pos++) {
        $char = $code[$pos];
        $target = PAIRS[$char] ?? null;
        $newChunk = $target !== null;
        if ($newChunk) {
            $end = parse($code, $pos + 1, $target);
            if ($end === null) {
                break;
            }
            $pos = $end;
        } elseif ($char === $until) {
            return $pos;
        } else {
            throw new ParserException($until, $char, $pos);
        }
    }
    // unexpected end?
    return null;
}

$score = 0;

foreach ($input as $n => $line) {
    $firstChar = $line[0];
    try {
        parse($line, 1, PAIRS[$firstChar]);
    } catch (ParserException $e) {
        $score += $points = $scoreLookup[$e->found];
//        echo sprintf('Parse exception at line %d; %s -> %d points', $n + 1, $e->getMessage(), $points), \PHP_EOL;
    }
}

echo '[Part 1] total syntax error score: ', $score, \PHP_EOL;
