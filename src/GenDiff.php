<?php

namespace Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\format;

function genDiff($pathToFile1, $pathToFile2, $format = 'pretty')
{
    [$arrBefore, $arrAfter] = parse($pathToFile1, $pathToFile2);
    $tree = buildDiffTree($arrBefore, $arrAfter);
    $result = format($tree, $format);
    return $result;
}

function buildDiffTree($before, $after)
{
    $keysBefore = array_keys($before);
    $keysAfter = array_keys($after);
    $keysUnique = array_values(array_unique(array_merge($keysBefore, $keysAfter)));

    $tree = array_map(function ($key) use ($keysBefore, $before, $keysAfter, $after) {
        if (!in_array($key, $keysBefore)) {
            return buildNode($key, ['type' => 'added', 'value' => $after[$key]]);
        }
        if (!in_array($key, $keysAfter)) {
            return buildNode($key, ['type' => 'deleted', 'value' => $before[$key]]);
        }
        if (is_array($before[$key]) && is_array($after[$key])) {
            return buildNode($key, ['type' => 'nested', 'children' => buildDiffTree($before[$key], $after[$key])]);
        }
        if ($before[$key] == $after[$key]) {
            return buildNode($key, ['type' => 'unchanged', 'value' => $before[$key]]);
        }
        if ($before[$key] !== $after[$key]) {
            return buildNode($key, ['type' => 'changed', 'valueAfter' => $after[$key], 'valueBefore' => $before[$key]]);
        }
    }, $keysUnique);
    
    return $tree;
}

function buildNode($key, $params)
{
    $default = [
        'name' => $key,
        'type' => 'fixed'
    ];
    return array_merge($default, $params);
}
