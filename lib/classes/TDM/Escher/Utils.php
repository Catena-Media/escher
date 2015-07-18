<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2014 Twist Digital Media
 * @package \TDM\Escher
 * @license https://raw.github.com/twistdigital/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * Utils
 * This mostly exists to tidy up inconsistencies in the argument order in the PHP API, as well
 * as add a few other useful functions which are missing from PHP's otherwise verbose suite.
 *
 * @author Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2015 Twist Digital Media
 */
class Utils
{
    /**
     * Apply a callback function to each element of an array
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function arrayMap(Array $array, $callback)
    {
        return array_map($callback, $array);
    }

    /**
     * Discard all but the keys named in the second parameter
     *
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function arrayPick(Array $array, $keys)
    {
        if (is_scalar($keys)) {
            $keys = array($keys);
        }
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Discard the keys named in the second parameter
     *
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function arrayOmit(Array $array, $keys)
    {
        if (is_scalar($keys)) {
            $keys = array($keys);
        }
        return array_diff_key($array, array_flip($keys));
    }

    /**
     * Return a portion of the array, starting at index $start and continuing for $length elements
     *
     * @param array $array
     * @param int $start - The initial offset
     * @param int $length (optional) - The length of the slice, or null for "the rest of the array"
     * @param bool $preserve - Should keys be preserved, default no
     * @return array
     */
    public static function arraySlice(Array $array, $start, $length = null, $preserve = NO)
    {
        return array_slice($array, $start, $length, !!$preserve);
    }

    /**
     * Discard array elements, based upon a callback function
     *
     * @param array $array
     * @param callable $callback, return truthy to keep
     * @return array
     */
    public static function arrayFilter(Array $array, $callback)
    {
        $array = array_filter($array, $callback);
        sort($array);
        return $array;
    }
}
