<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse($pathToFile1, $pathToFile2)
{
    $ext = pathinfo($pathToFile1)['extension'];
    switch ($ext) {
        case 'json':
            return parseFlatJson($pathToFile1, $pathToFile2);
        case 'yml':
            return parseFlatYml($pathToFile1, $pathToFile2);
        case 'yaml':
            return parseFlatYml($pathToFile1, $pathToFile2);
    }
}

function parseFlatJson($pathToFile1, $pathToFile2)
{
    $file1 = file_get_contents($pathToFile1);
    $file2 = file_get_contents($pathToFile2);

    return [json_decode($file1, true), json_decode($file2, true)];
}

function parseFlatYml($pathToFile1, $pathToFile2)
{
    $file1 = Yaml::parseFile($pathToFile1);
    $file2 = Yaml::parseFile($pathToFile2);

    return [$file1, $file2];
}
