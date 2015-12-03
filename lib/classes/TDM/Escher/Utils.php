<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2015 Digital Design Labs
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * Utils
 * This mostly exists to tidy up inconsistencies in the argument order in the PHP API, as well
 * as add a few other useful functions which are missing from PHP's otherwise verbose suite.
 * @author Mike Hall
 * @copyright 2015 Twist Digital Media
 */
class Utils
{
    /**
     * Apply a callback function to each element of an array
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function arrayMap(array $array, $callback)
    {
        return array_map($callback, $array);
    }

    /**
     * Discard all but the keys named in the second parameter
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function arrayPick(array $array, $keys)
    {
        if (is_scalar($keys)) {
            $keys = array($keys);
        }
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * Discard the keys named in the second parameter
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function arrayOmit(array $array, $keys)
    {
        if (is_scalar($keys)) {
            $keys = array($keys);
        }
        return array_diff_key($array, array_flip($keys));
    }

    /**
     * Return a portion of the array, starting at index $start and continuing for $length elements
     * @param array $array
     * @param int $start - The initial offset
     * @param int $length (optional) - The length of the slice, or null for "the rest of the array"
     * @param bool $preserve - Should keys be preserved, default no
     * @return array
     */
    public static function arraySlice(array $array, $start, $length = null, $preserve = NO)
    {
        return array_slice($array, $start, $length, !!$preserve);
    }

    /**
     * Reduce an array to a scalar value. Simple wrapper around the standard function
     * @param array $array
     * @param callable $callback
     * @param mixed $initial (optional)
     * @return mixed The reduced value
     */
    public static function arrayReduce(array $array, $callback, $initial = null)
    {
        return array_reduce($array, $callback, $initial);
    }

    /**
     * Discard array elements, based upon a callback function
     * @param array $array
     * @param callable $callback, return truthy to keep
     * @return array
     */
    public static function arrayFilter(array $array, $callback)
    {
        $array = array_filter($array, $callback);
        sort($array);
        return $array;
    }

    /**
     * Does the needle exist in the haystack?
     * @param array $haystack
     * @param mixed $needle
     * @return boolean
     */
    public static function inArray(array $haystack, $needle)
    {
        return in_array($needle, $haystack);
    }

    /**
     * Flatten an array-of-arrays down into a single linear array.
     * Optional $deep parameter determines whether to recurse into sub arrays, or just do a single pass
     * @param array $array
     * @param boolean $deep (optional)
     * @return array
     */
    public static function arrayFlatten(array $array, $deep = NO)
    {
        if ($deep === YES) {
            $output = array();
            array_walk_recursive($array, function ($v) use (&$output) {
                $output[] = $v;
            });
            return $output;
        }

        return call_user_func_array("array_merge", $array);
    }
}
