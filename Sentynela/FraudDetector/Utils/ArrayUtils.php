<?php

namespace Sentynela\FraudDetector\Utils;

/**
 * Class ArrayUtils. Some Utils.
 * @package Sentynela\FraudDetector\Utils
 */
class ArrayUtils
{
    public static function getValueKeyChecked($key, $array): string
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return '';
    }
}
