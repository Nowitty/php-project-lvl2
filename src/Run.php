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
    //genDiff('firstJson.json', 'secondJson.json');
    $args = \Docopt::handle(DOC, ['version'=> VERSION]);
}