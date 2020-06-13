<?php

namespace Differ;

use function Differ\Parser\parse;
use function Differ\Render\render;

function genDiff($pathToFile1, $pathToFile2)
{
    [$arrBefore, $arrAfter] = parse($pathToFile1, $pathToFile2);
    //var_dump($arrBefore);
    $tree = buildDiffTree($arrBefore, $arrAfter);
    //var_dump($tree);
    $result = render($tree);
    print($result);
    // return $result;
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
        if (in_array($key, $keysAfter) && !in_array($key, $keysBefore)) {
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
        if (!in_array($key, $keysAfter) && in_array($key, $keysBefore)) {
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
// function buildStr($arrBefore, $arrAfter)
// {
//     $keysBefore = array_keys($arrBefore);
//     $keysAfter = array_keys($arrAfter);
//     $keysDiff = array_diff($keysAfter, $keysBefore);
//     $result = [
//         'notChanged' => '',
//         'changed' => '',
//         'deleted' => '',
//         'added' => ''
//     ];
//     $result['added'] = array_reduce($keysDiff, fn($acc, $key) =>
//     $acc .= "  + {$key}: " . correctValue($arrAfter[$key]) . "\n", '');

//     foreach ($keysBefore as $key) {
//         if (array_key_exists($key, $arrAfter)) {
//             if ($arrBefore[$key] === $arrAfter[$key]) {
//                 $result['notChanged'] .= "    {$key}: " . correctValue($arrBefore[$key]) . "\n";
//                 continue;
//             }
//         }
//         if (!array_key_exists($key, $arrAfter)) {
//             $result['deleted'] .= "  - {$key}: " . correctValue($arrBefore[$key]) . "\n";
//             continue;
//         }
//         $result['changed'] .= "  + {$key}: " . correctValue($arrAfter[$key]) . "\n";
//         $result['changed'] .= "  - {$key}: " . correctValue($arrBefore[$key]) . "\n";
//     }

//     return "{\n" . implode('', $result) . "}";
// }

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
