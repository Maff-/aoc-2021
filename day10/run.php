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

class UnexpectedEndParserException extends ParserException
{
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
    throw new UnexpectedEndParserException($until, null, $pos);
}

$score = 0;

foreach ($input as $n => $line) {
    $firstChar = $line[0];
    try {
        parse($line, 1, PAIRS[$firstChar]);
    } catch (UnexpectedEndParserException) {
    } catch (ParserException $e) {
        $score += $points = $scoreLookup[$e->found];
//        echo sprintf('Parse exception at line %d; %s -> %d points', $n + 1, $e->getMessage(), $points), \PHP_EOL;
    }
}

echo '[Part 1] total syntax error score: ', $score, \PHP_EOL;

// Part 2

$scoreLookup = [
    ')' => 1,
    ']' => 2,
    '}' => 3,
    '>' => 4,
];

$scores = [];

foreach ($input as $n => $line) {
    $score = 0;
    $pos = 0;
    while (true) {
        $char = $line[$pos];
        $len = strlen($line);
        try {
            $lastPos = parse($line, $pos + 1, PAIRS[$char]);
            if ($lastPos === $len - 1) {
                break;
            }
            $pos = $lastPos + 1;
        } catch (UnexpectedEndParserException $e) {
            $line .= $e->expected;
            $score = ($score * 5) + $scoreLookup[$e->expected];
        } catch (ParserException) {
            continue 2;
        }
    }
    if ($score !== 0) {
        $scores[] = $score;
    }
}

sort($scores);
$score = $scores[(count($scores) - 1) / 2];

echo '[Part 2] middle auto complete score: ', $score, \PHP_EOL;
