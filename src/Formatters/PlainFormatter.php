<?php

namespace Differ\Formatters\Plain;

function render($tree, $parents = '')
{
    if ($parents !== '') {
        $parents .= '.';
    }
    $result = array_map(function($node) use ($parents) {
        if ($node['type'] == 'parent') {
            if ($node['state'] == 'deleted') {
                return "Property '{$parents}{$node['name']}' was removed\n";
            }
            if ($node['state'] == 'added') {
                return "Property '{$parents}{$node['name']}' was added with value: 'complex value'\n";
            }
            return render($node['children'], "{$parents}{$node['name']}");
        } else {
            switch ($node['state']) {
                case 'added':
                    return "Property '{$parents}{$node['name']}' was added with value: '" . 
                    correctValue($node['value']) . "'\n";
                case 'deleted':
                    return "Property '{$parents}{$node['name']}' was removed\n";
                case 'changed':
                    return "Property '{$parents}{$node['name']}' was changed. From '" .
                    correctValue($node['valueBefore']) . "' to '" . correctValue($node['valueAfter']) . "'\n";
            }
        }
    }, $tree);
    return implode('', $result);
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