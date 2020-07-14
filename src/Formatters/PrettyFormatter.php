<?php

namespace Differ\Formatters\Pretty;

function render($tree, $depth = 1)
{
    $result = array_map(function ($node) use ($depth) {
        $value = $node['value'] ?? '';
        $name = $node['name'] ?? '';

        $spaces = str_repeat(' ', $depth * 4 - 2);
        switch ($node['type']) {
            case 'nested':
                return "{$spaces}  {$name}: {\n" . render($node['children'], $depth + 1) . "\n{$spaces}  }";
            case 'added':
                return $spaces . "+ " . $name . ": " . renderValue($value, $depth);
            case 'deleted':
                return $spaces . "- " . $name . ": " . renderValue($value, $depth);
            case 'changed':
                $before = $spaces . "- " . $name . ": " . renderValue($node['valueBefore'], $depth);
                $after = $spaces . "+ " . $name . ": " . renderValue($node['valueAfter'], $depth);
                return $after . "\n" . $before;
            case 'unchanged':
                return $spaces . '  ' . $name . ": " . renderValue($value, $depth);
        }
    }, $tree);
    
    return implode("\n", $result);
}

function renderValue($value, $depth)
{
    switch (gettype($value)) {
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'array':
            return renderArrayValue($value, $depth);
        default:
            return $value;
    }
}

function renderArrayValue($value, $depth)
{
    $spaces = str_repeat(' ', $depth * 4);
    $keys = array_keys($value);
    $values = array_values($value);

    $result = array_map(function ($key, $value) use ($spaces, $depth) {
        $renderedValue = renderValue($value, $depth);
        return "{$spaces}    {$key}: {$renderedValue}";
    }, $keys, $values);

    return "{\n" . implode("\n", $result) . "\n{$spaces}}";
}
