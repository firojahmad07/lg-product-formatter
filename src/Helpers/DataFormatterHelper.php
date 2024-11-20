<?php
namespace App\Helpers;

class DataFormatterHelper
{
    CONST ATTRIBUTE_AND_GROUP_REGEX = '/^(?<attribute>[a-zA-Z0-9 \/&+-]+)-group-(?<group>[a-zA-Z0-9 \/&+-]+)$/';

    public static function parseStringWithRegex(string $input): array
    {
        $attributeData = explode('-group-', $input);
        return [
            'attribute' => $attributeData[0],
            'group' => $attributeData[1],
        ];

    }
}
