<?php

namespace Differ\Formatters\Plain;

function render($tree, $parents = '')
{
    if ($parents !== '') {
        $parents .= '.';
    }
    $result = array_map(function ($node) use ($parents) {
        $value = $node['value'] ?? '';
        $name = $node['name'] ?? '';

        switch ($node['type']) {
            case 'nested':
                return render($node['children'], "{$parents}{$name}");
            case 'added':
                return "Property '{$parents}{$name}' was added with value: '" . renderValue($value) . "'\n";
            case 'deleted':
                return "Property '{$parents}{$name}' was removed\n";
            case 'changed':
                return "Property '{$parents}{$name}' was changed. From '" .
                 renderValue($node['valueBefore']) . "' to '" . renderValue($node['valueAfter']) . "'\n";
        }
    }, $tree);

    return implode("", $result);
}

function renderValue($value)
{
    switch (gettype($value)) {
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'array':
            return 'complex value';
        default:
            return $value;
    }
}
