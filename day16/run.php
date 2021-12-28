<?php

declare(strict_types=1);

$input = <<<EXAMPLE
A0016C880162017C3686B18A3D4780
EXAMPLE;

$input = file_get_contents('input.txt');

$input = trim($input);

// Part 1

function hexToBin(string $data): string
{
    $bin = '';
    foreach (str_split($data) as $nibble) {
        $bin .= str_pad(base_convert($nibble, 16, 2), 4, '0', \STR_PAD_LEFT);
    }
    return $bin;
}

function peak(string $data, int $pos, int $length): int
{
    return bindec(substr($data, $pos, $length));
}

function read(string $data, int &$pos, int $length): int
{
    $result = peak($data, $pos, $length);
    $pos += $length;
    return $result;
}

function readPacket(string $data, int &$pos = 0): int|array
{
    global $versionSum;
    $version = read($data, $pos, 3);
    $versionSum += $version;
    $type = read($data, $pos, 3);

    switch ($type) {
        case 4: // literal value
            $value = 0;
            while (true) {
                $last = !(bool)read($data, $pos, 1);
                $group = read($data, $pos, 4);
                $value <<= 4;
                $value += $group;
                if ($last) {
                    break;
                }
            }
            return $value;
        default: // operator
            $lengthId = read($data, $pos, 1);
            $subPackets = [];
            switch ($lengthId) {
                case 0:
                    $subPacketsLength = read($data, $pos, 15);
                    $end = $pos + $subPacketsLength;
                    while ($pos < $end) {
                        $subPackets[] = readPacket($data, $pos);
                    }
                    return $subPackets;
                case 1:
                    $subPacketsCount = read($data, $pos, 11);
                    for ($n = 0; $n < $subPacketsCount; $n++) {
                        $subPackets[] = readPacket($data, $pos);
                    }
                    return $subPackets;
            }
            break;
    }
}

function sumVersions(string $data): int
{
    global $versionSum;
    readPacket($data);
    return $versionSum;
}

$versionSum = 0;
sumVersions(hexToBin($input));

echo '[Part 1] Sum of version numbers of all packets: ', $versionSum, \PHP_EOL;
