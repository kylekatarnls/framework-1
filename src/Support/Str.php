<?php
/**
 * @author Franck Dakia <dakiafranck@gmail.com>
 * Date: 19/02/16
 * Time: 10:51
 */

namespace Bow\Support;


class Str
{
    /**
     * upper case
     *
     * @param string $str
     * @return array|string
     */
    public static function upper($str)
    {
        if (is_string($str)) {
            $str = mb_strtoupper($str, "UTF-8");
        }

        return $str;
    }

    /**
     * lower case
     *
     * @param string $str
     * @return array|string
     */
    public static function lower($str)
    {
        if (is_string($str)) {
            $str = mb_strtolower($str, "UTF-8");
        }

        return $str;
    }

    /**
     * slice
     *
     * @param string $str
     * @param $start
     * @param null $end
     * @return string
     */
    public static function slice($str, $start, $end = null)
    {
        $sliceStr = "";
        if (is_string($str)) {
            if ($end === null) {
                $end = static::length($str);
            }
            if ($start < $end) {
                $sliceStr = mb_substr($str, $start, $end);
            }
        }
        return $sliceStr;
    }

    /**
     * split
     *
     * @param string $pattern
     * @param string $str
     * @param null $limit
     * @return array
     */
    public static function split($pattern, $str, $limit = null)
    {
        return mb_split($pattern, $str, $limit);
    }

    /**
     * match
     *
     * @param string $pattern
     * @param string $str
     * @param array $match
     * @return int
     */
    public static function match($pattern, $str, & $match)
    {
        return preg_match($pattern, $str, $match);
    }

    /**
     * @param $search
     * @param $str
     * @return int
     */
    public static function pos($search, $str)
    {
        return mb_strpos($search, $str, null, "UTF-8");
    }

    /**
     * @param $search
     * @param $str
     * @return bool
     */
    public static function contains($search, $str)
    {
        if ($search === $str) {
            return true;
        } else {
            if (-1 == static::pos($search, $str)) {
                return true;
            }
        }

        return false;
    }

    /**
     * replace
     *
     * @param $pattern
     * @param $replaceBy
     * @param $str
     */
    public static function replace($pattern, $replaceBy, $str)
    {
        preg_match($pattern, $replaceBy, $str);
    }

    /**
     * capitalize
     *
     * @param $str
     * @return string
     */
    public static function capitalize($str)
    {
        return ucwords($str);
    }

    /**
     * len
     *
     * @param $str
     * @return int
     */
    public static function len($str)
    {
        return mb_strlen($str);
    }

    /**
     * wordify
     *
     * @param $str
     * @return array
     */
    public static function wordify($str)
    {
        $words = static::split(" ", $str);
        foreach($words as $key => $values) {
            $words[$key] = static::capitalize($values);
        }

        return $words;
    }

    /**
     * repeat
     *
     * @param $str
     * @param $number
     * @return string
     */
    public static function repeat($str, $number)
    {
        return str_repeat($str, $number);
    }

    /**
     * randomize
     *
     * @param int $size
     * @return string
     */
    public static function randomize($size = 16)
    {
        return static::slice(0, $size, str_shuffle('#*$@abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012356789'));
    }
}