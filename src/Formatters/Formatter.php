<?php

namespace Differ\Formatters;

function format($tree, $format)
{
    $format = mb_strtolower($format);
    switch ($format) {
        case 'pretty':
            return "{\n" . Pretty\render($tree) . "\n}";
        case 'plain':
            return Plain\render($tree);
        case 'json':
            return Json\render($tree);
        default:
            echo 'unknow format';
    }
}
