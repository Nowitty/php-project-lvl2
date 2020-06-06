<?php

namespace Differ;

function genDiff($pathToFile1, $pathToFile2)
{
    var_dump(pathinfo($pathToFile1));
    $file1 = file_get_contents($pathToFile1);
    $file2 = file_get_contents($pathToFile2);
    $arrBefore = json_decode($file1, true);
    $arrAfter = json_decode($file2, true);
    foreach ($arrAfter as $key => $value) {
        $value = correctValue($value);
        if (!array_key_exists($key, $arrBefore)) {
            $result[] = "+ {$key}: {$value}\n";
        }
        if (array_key_exists($key, $arrBefore) && $arrBefore[$key] === $value) {
            $result[] = "  {$key}: {$value}\n";
        }
        if (array_key_exists($key, $arrBefore) && $arrBefore[$key] !== $value) {
            $result[] = '-' . ' ' . $key . ':' . correctValue($arrBefore[$key]) . "\n";
            $result[] = "+ {$key}: {$value}\n";
        }
    }
    $result = implode('', $result);
    return $result;
}

function correctValue($value)
{
    if ($value === true) {
        return 'true';
    }
    return $value;
}