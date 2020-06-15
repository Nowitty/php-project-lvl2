<?php

namespace Differ;

use function Differ\Parser\parse;

function genDiff($pathToFile1, $pathToFile2, $format = 'pretty')
{
    [$arrBefore, $arrAfter] = parse($pathToFile1, $pathToFile2);
    $tree = buildDiffTree($arrBefore, $arrAfter);
    $result = format($tree, $format);
    return $result;
}

function format($tree, $format)
{
    $format = mb_strtolower($format);
    switch ($format) {
        case 'pretty':
            return "{\n" . Formatters\Pretty\render($tree) . "}";
        case 'plain':
            return Formatters\Plain\render($tree);
        default:
            echo 'unknow format';
    }
}

function buildDiffTree($before, $after)
{
    $keysBefore = array_keys($before);
    $keysAfter = array_keys($after);
    $keysUnique = array_unique(array_merge($keysBefore, $keysAfter));
    $tree = array_reduce($keysUnique, function ($acc, $key) use ($keysBefore, $before, $keysAfter, $after) {
        if (in_array($key, $keysAfter) && in_array($key, $keysBefore)) {
            if (is_array($before[$key]) && is_array($after[$key])) {
                $acc[] = [
                    'name' => $key,
                    'type' => 'parent',
                    'state' => 'notChanged',
                    'children' => buildDiffTree($before[$key], $after[$key])
                ];
                return $acc;
            } else {
                if ($before[$key] === $after[$key]) {
                    $acc[] = [
                        'name' => $key,
                        'type' => 'child',
                        'state' => 'notChanged',
                        'value' => $before[$key]
                    ];
                    return $acc;
                } else {
                    $acc[] = [
                        'name' => $key,
                        'type' => 'child',
                        'state' => 'changed',
                        'valueAfter' => $after[$key],
                        'valueBefore' => $before[$key]
                    ];
                    return $acc;
                }
            }
        }
        if (!in_array($key, $keysBefore)) {
            if (is_array($after[$key])) {
                $acc[] = [
                    'name' => $key,
                    'type' => 'parent',
                    'state' => 'added',
                    'children' => buildDiffTree([], $after[$key])
                ];
            } else {
                $acc[] = [
                    'name' => $key,
                    'type' => 'child',
                    'state' => 'added',
                    'value' => $after[$key]
                ];
            }
        }
        if (!in_array($key, $keysAfter)) {
            if (is_array($before[$key])) {
                $acc[] = [
                    'name' => $key,
                    'type' => 'parent',
                    'state' => 'deleted',
                    'children' => buildDiffTree($before[$key], [])
                ];
            } else {
                $acc[] = [
                    'name' => $key,
                    'type' => 'child',
                    'state' => 'deleted',
                    'value' => $before[$key]
                ];
            }
        }
        return $acc;
    }, []);

    return $tree;
}
