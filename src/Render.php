<?php

namespace Differ\Render;


// function render($tree)
// {
//     // var_dump($tree);
//     // echo "\n --------\n";
//     $result = array_map(function($node) {
//         //
//         if ($node['type'] == 'parent') {
//             if ($node['state'] == 'notChanged') {
//                 return "    {$node['name']}: {\n" . render($node['children']) . "    }\n";
//             }
//             if ($node['state'] == 'added') {
//                 return "  + {$node['name']}: {\n" . render($node['children']) . "    }\n";
//             }
//             if ($node['state'] == 'deleted') {
//                 return "  - {$node['name']}: {\n" . render($node['children']) . "    }\n";
//             }
            
//         } else {
//             if ($node['state'] == 'notChanged') {
//                 return "        {$node['name']}: {$node['value']}\n    ";
//             }
//             if ($node['state'] == 'changed') {
//                 $before = "      - {$node['name']}: {$node['valueBefore']}\n    ";
//                 $after = "      + {$node['name']}: {$node['valueAfter']}\n    ";
//                 return $after . $before;
//             }
//             if ($node['state'] == 'added') {
//                 return "  + {$node['name']}: {$node['value']}\n    ";
//             }
//             if ($node['state'] == 'deleted') {
//                 return "  - {$node['name']}: {$node['value']}\n    ";
//             }
//         }
//     }, $tree);

//     return implode('',$result);
// }

function render($tree, $depth = 1)
{
    $result = array_reduce($tree, function($acc, $node) use ($depth) {
        $spaces = str_repeat(' ', $depth * 4 - 2);
        if ($node['type'] == 'parent') {
            if ($node['state'] == 'notChanged') {
                $acc .= "{$spaces}  {$node['name']}: {\n" . render($node['children'], $depth + 1) . "{$spaces}  }\n";
            }
            if ($node['state'] == 'added') {
                $acc .= "{$spaces}+ {$node['name']}: {\n" . render($node['children'], $depth + 1) . "{$spaces}  }\n";
            }
            if ($node['state'] == 'deleted') {
                $acc .= "{$spaces}- {$node['name']}: {\n" . render($node['children'], $depth + 1) . "{$spaces}  }\n";
            }
            
        } else {
            if ($node['state'] == 'notChanged') {
                $acc .= "{$spaces}  {$node['name']}: {$node['value']}\n";
            }
            if ($node['state'] == 'changed') {
                $before = "{$spaces}- {$node['name']}: {$node['valueBefore']}\n";
                $after = "{$spaces}+ {$node['name']}: {$node['valueAfter']}\n";
                $acc .= $after . $before;
            }
            if ($node['state'] == 'added') {
                $acc .= "{$spaces}+ {$node['name']}: {$node['value']}\n";
            }
            if ($node['state'] == 'deleted') {
                $acc .= "{$spaces}- {$node['name']}: {$node['value']}\n";
            }
        }
        return $acc;
    }, '');

    return $result;
}
