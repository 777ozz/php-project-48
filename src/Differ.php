<?php

namespace Differ\Differ;

function getValue(object $data, string $key): mixed
{
    $value = $data->$key;
    $value = is_bool($value) ? var_export($value, true) : $value;
    return $value;
}

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $fileContent1 = file_get_contents($pathToFile1);
    $fileContent2 = file_get_contents($pathToFile2);
    $data1 = json_decode($fileContent1);
    $data2 = json_decode($fileContent2);
    $keys1 = array_keys(get_object_vars($data1));
    $keys2 = array_keys(get_object_vars($data2));
    $keys = array_unique(array_merge($keys1, $keys2));
    sort($keys);
    $result = [];
    foreach ($keys as $key) {
        $isKeyInKeys1 = in_array($key, $keys1);
        $isKeyInKeys2 = in_array($key, $keys2);
        if ($isKeyInKeys1 && $isKeyInKeys2) {
            $value1 = getValue($data1, $key);
            $value2 = getValue($data2, $key);
            if ($value1 === $value2) {
                $result[] = "  {$key}: {$value1}";
            } else {
                $result[] = "- {$key}: {$value1}";
                $result[] = "+ {$key}: {$value2}";
            }
        } else {
            if ($isKeyInKeys1) {
                $value1 = getValue($data1, $key);
                $result[] = "- {$key}: {$value1}";
            } else {
                $value2 = getValue($data2, $key);
                $result[] = "+ {$key}: {$value2}";
            }            
        }
    }
    $resultImplode = implode("\n  ", $result);
    $resultString = "{\n  {$resultImplode}\n}\n";
    return $resultString;
}
