<?php

namespace Gendiff\Parser;

function parser($path)
{
    $fileContent = file_get_contents($path);
    $data = json_decode($fileContent);
    print_r($data);
}
