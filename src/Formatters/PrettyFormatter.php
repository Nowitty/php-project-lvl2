<?php

namespace Differ\Formatters\Pretty;

function render($tree, $depth = 1)
{
    $result = array_reduce($tree, function ($acc, $node) use ($depth) {
        $spaces = str_repeat(' ', $depth * 4 - 2);
        if ($node['type'] == 'parent') {
            if ($node['state'] == 'notChanged') {
                $acc .= "{$spaces}  {$node['name']}: {\n" . render($node['children'], $depth + 1) . "{$spaces}  }\n";
            }
            if ($node['state'] == 'added') {
                $newChildren = correctChildren($node['children']);
                $acc .= "{$spaces}+ {$node['name']}: {\n" . render($newChildren, $depth + 1) . "{$spaces}  }\n";
            }
            if ($node['state'] == 'deleted') {
                $newChildren = correctChildren($node['children']);
                $acc .= "{$spaces}- {$node['name']}: {\n" . render($newChildren, $depth + 1) . "{$spaces}  }\n";
            }
        } else {
            if ($node['state'] == 'notChanged') {
                $acc .= "{$spaces}  {$node['name']}: " . correctValue($node['value']) . "\n";
            }
            if ($node['state'] == 'changed') {
                $before = "{$spaces}- {$node['name']}: " . correctValue($node['valueBefore']) . "\n";
                $after = "{$spaces}+ {$node['name']}: " . correctValue($node['valueAfter']) . "\n";
                $acc .= $after . $before;
            }
            if ($node['state'] == 'added') {
                $acc .= "{$spaces}+ {$node['name']}: " . correctValue($node['value']) . "\n";
            }
            if ($node['state'] == 'deleted') {
                $acc .= "{$spaces}- {$node['name']}: " . correctValue($node['value']) . "\n";
            }
        }
        return $acc;
    }, '');

    return $result;
}

function correctChildren($node)
{
    $res = array_map(function ($item) {
        $item['state'] = 'notChanged';
        if ($item['type'] == 'parent') {
            return correctChildren($item['children']);
        }
        return $item;
    }, $node);

    return $res;
}

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
