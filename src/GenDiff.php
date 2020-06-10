<?php

namespace Differ;

use function Differ\Parser\parse;

function genDiff($pathToFile1, $pathToFile2)
{
    [$arrBefore, $arrAfter] = parse($pathToFile1, $pathToFile2);
    $result = buildStr($arrBefore, $arrAfter);
    return $result;
}

function buildStr($arrBefore, $arrAfter)
{
    $keysBefore = array_keys($arrBefore);
    $keysAfter = array_keys($arrAfter);
    $keysDiff = array_diff($keysAfter, $keysBefore);
    $result = [
        'notChanged' => '',
        'changed' => '',
        'deleted' => '',
        'added' => ''
    ];
    $result['added'] = array_reduce($keysDiff, fn($acc, $key) =>
    $acc .= "  + {$key}: " . correctValue($arrAfter[$key]) . "\n", '');

    foreach ($keysBefore as $key) {
        if (array_key_exists($key, $arrAfter)) {
            if ($arrBefore[$key] === $arrAfter[$key]) {
                $result['notChanged'] .= "    {$key}: " . correctValue($arrBefore[$key]) . "\n";
                continue;
            }
        }
        if (!array_key_exists($key, $arrAfter)) {
            $result['deleted'] .= "  - {$key}: " . correctValue($arrBefore[$key]) . "\n";
            continue;
        }
        $result['changed'] .= "  + {$key}: " . correctValue($arrAfter[$key]) . "\n";
        $result['changed'] .= "  - {$key}: " . correctValue($arrBefore[$key]) . "\n";
    }

    return "{\n" . implode('', $result) . "}";
}

// correct bool value
function correctValue($value)
{
    if ($value === true) {
        return 'true';
    }
    if ($value === false) {
        return 'false';
    }
    return $value;
}
