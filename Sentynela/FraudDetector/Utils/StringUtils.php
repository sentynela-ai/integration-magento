<?php

namespace Sentynela\FraudDetector\Utils;

/**
 * Class StringUtils. Some Utils.
 * @package Sentynela\FraudDetector\Utils
 * @author Jean Poffo
 */
class StringUtils
{

    /**
     * @param string ...$params
     * @return string
     */
    public static function createPhrase(...$params): string
    {
        return implode(' ', array_filter($params));
    }

    /**
     * @param $string
     * @return int
     */
    public static function toInteger($string): int
    {
        return (int) preg_replace("/[^0-9]/", "", $string);
    }

    public static function camelCaseToSnakeCase($string): string
    {
        return join('', array_map(function ($character) {
            return ctype_upper($character) ? '_' . strtolower($character) : $character;
        }, str_split($string)));
    }
}
