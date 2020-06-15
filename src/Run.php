<?php

namespace Differ;

use function Differ\genDiff;

const VERSION = 'gendiff v0.1';
const DOC = "
Generate diff

Usage:
    gendiff (-h | --help)
    gendiff (-v | --version)
    gendiff [--format <fmt>] <firstfile> <secondfile>

Options:
    -h --help     Show this screen
    -v --version     Show version
    --format <fmt>    Report format [default: pretty]";

function run()
{
    $args = \Docopt::handle(DOC, ['version' => VERSION]);
    $file1 = $args['<firstfile>'];
    $file2 = $args['<secondfile>'];
    $format = $args['--format'];
    $diff = genDiff($file1, $file2, $format);
    print_r($diff);
}
