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

// Part 2

function processPacket(string $data, int &$pos = 0): int
{
    $version = read($data, $pos, 3);
    $type = read($data, $pos, 3);

    // literal value
    if ($type === 4) {
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
    }

    // operator
    $lengthId = read($data, $pos, 1);
    $subPackets = [];
    switch ($lengthId) {
        case 0:
            $subPacketsLength = read($data, $pos, 15);
            $end = $pos + $subPacketsLength;
            while ($pos < $end) {
                $subPackets[] = processPacket($data, $pos);
            }
            break;
        case 1:
            $subPacketsCount = read($data, $pos, 11);
            for ($n = 0; $n < $subPacketsCount; $n++) {
                $subPackets[] = processPacket($data, $pos);
            }
            break;
    }

    switch ($type) {
        case 0: // sum
            return array_sum($subPackets);
        case 1: // product
            return array_product($subPackets);
        case 2: // minimum
            return min($subPackets);
        case 3: // maximum
            return max($subPackets);
        case 5: // greater than
            return $subPackets[0] > $subPackets[1] ? 1 : 0;
        case 6: // less than
            return $subPackets[0] < $subPackets[1] ? 1 : 0;
        case 7: // equal to
            return $subPackets[0] === $subPackets[1] ? 1 : 0;
    }
}

//assert(processPacket(hexToBin('C200B40A82')) === 3, 'C200B40A82 => 3');
//assert(processPacket(hexToBin('04005AC33890')) === 54, 'C200B40A82 => 54');
//assert(processPacket(hexToBin('880086C3E88112')) === 7, '880086C3E88112 => 7');
//assert(processPacket(hexToBin('CE00C43D881120')) === 9, 'CE00C43D881120 => 9');
//assert(processPacket(hexToBin('D8005AC2A8F0')) === 1, 'D8005AC2A8F0 => 1');
//assert(processPacket(hexToBin('F600BC2D8F')) === 0, 'F600BC2D8F => 0');
//assert(processPacket(hexToBin('9C005AC2F8F0')) === 0, '9C005AC2F8F0 => 0');
//assert(processPacket(hexToBin('9C0141080250320F1802104A08')) === 1, '9C0141080250320F1802104A08 => 1');

$result = processPacket(hexToBin($input));
echo '[Part 2] Evaluation of BITS transmission: ', $result, \PHP_EOL;
