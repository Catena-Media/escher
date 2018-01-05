<?php

/**
 * Escher Framework
 * @copyright Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * Collection
 * A suite of function for working with PHP arrays.
 *
 * @author Mike Hall
 */
class Collection
{
    /**
     * map()
     * Apply a callback function to each element of an array
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function map(array $array, $callback)
    {
        return array_map($callback, $array);
    }

    /**
     * pick()
     * Discard all but the keys named in the second parameter
     *
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function pick(array $array, $keys)
    {
        if (is_scalar($keys)) {
            $keys = array($keys);
        }

        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * omit()
     * Discard the keys named in the second parameter
     *
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function omit(array $array, $keys)
    {
        if (is_scalar($keys)) {
            $keys = array($keys);
        }

        return array_diff_key($array, array_flip($keys));
    }

    /**
     * slice()
     * Return a portion of the array, starting at index $start and continuing for $length elements
     *
     * @param array $array
     * @param int $start - The initial offset
     * @param int $length (optional) - The length of the slice, or null for "the rest of the array"
     * @param bool $preserve - Should keys be preserved, default no
     * @return array
     */
    public static function slice(array $array, $start, $length = null, $preserve = NO)
    {
        return array_slice($array, $start, $length, !!$preserve);
    }

    /**
     * reduce()
     * Reduce an array to a scalar value. Simple wrapper around the standard function
     *
     * @param array $array
     * @param callable $callback
     * @param mixed $initial (optional)
     * @return mixed The reduced value
     */
    public static function reduce(array $array, $callback, $initial = null)
    {
        if (is_null($initial) === YES) {
            $initial = array_shift($array);
        }
        return array_reduce($array, $callback, $initial);
    }

    /**
     * filter()
     * Discard array elements, based upon a callback function. Does not maintain key-value association.
     *
     * @param array $array
     * @param callable $callback, return truthy to keep
     * @return array
     */
    public static function filter(array $array, $callback)
    {
        if (is_string($callback) === YES) {
            $callback = function ($element) use ($callback) {
                return empty($element[$callback]) === NO;
            };
        }

        $array = array_filter($array, $callback);
        return array_values($array);
    }

    /**
     * has()
     * Does the needle exist in the haystack?
     *
     * @param array $haystack
     * @param mixed $needle
     * @return boolean
     */
    public static function has(array $haystack, $needle)
    {
        return in_array($needle, $haystack);
    }

    /**
     * hasKey()
     * Does the needle exist as a key in the haystack?
     *
     * @param array $haystack
     * @param mixed $needle
     * @return boolean
     */
    public static function hasKey(array $haystack, $needle)
    {
        return array_key_exists($needle, $haystack);
    }

    /**
     * flatten()
     * Flatten an array-of-arrays down into a single linear array.
     * Optional $deep parameter determines whether to recurse into sub arrays, or just do a single pass
     *
     * @param array $array
     * @param boolean $deep (optional)
     * @return array
     */
    public static function flatten(array $array, $deep = NO)
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

    /**
     * find()
     * Find the first array element which matches the supplied callback
     *
     * @param array $array
     * @param callable $callback
     * @return mixed matching element, or NULL for no match
     */
    public static function find(array $array, callable $callback)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key, $array)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * every()
     * Returns true if every element of the array matches a supplied callback
     *
     * @param array $array
     * @param callable $callback
     * @return boolean
     */
    public static function every(array $array, callable $callback)
    {
        foreach ($array as $key => $value) {
            if (!call_user_func($callback, $value, $key, $array)) {
                return NO;
            }
        }

        return YES;
    }

    /**
     * some()
     * Returns true if at least one element of the array matches a supplied callback
     *
     * @param array $array
     * @param callable $callback
     * @return boolean
     */
    public static function some(array $array, callable $callback)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key, $array)) {
                return YES;
            }
        }

        return NO;
    }
}
