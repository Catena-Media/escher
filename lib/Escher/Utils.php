<?php

/**
 * Escher Framework
 * @copyright Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * Utils
 * This mostly exists to tidy up inconsistencies in the argument order in the PHP API, as well
 * as add a few other useful functions which are missing from PHP's otherwise verbose suite.
 * @author Mike Hall
 */
class Utils
{
    /**
     * arrayMap()
     * Apply a callback function to each element of an array
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function arrayMap(array $array, $callback)
    {
        trigger_error("Deprecated function; use Collection::map instead", E_USER_NOTICE);
        return Collection::map($array, $callback);
    }

    /**
     * arrayPick()
     * Discard all but the keys named in the second parameter
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function arrayPick(array $array, $keys)
    {
        trigger_error("Deprecated function; use Collection::pick instead", E_USER_NOTICE);
        return Collection::pick($array, $keys);
    }

    /**
     * arrayOmit()
     * Discard the keys named in the second parameter
     * @param array $array
     * @param mixed $keys
     * @return array
     */
    public static function arrayOmit(array $array, $keys)
    {
        trigger_error("Deprecated function; use Collection::omit instead", E_USER_NOTICE);
        return Collection::omit($array, $keys);
    }

    /**
     * arraySlice()
     * Return a portion of the array, starting at index $start and continuing for $length elements
     * @param array $array
     * @param int $start - The initial offset
     * @param int $length (optional) - The length of the slice, or null for "the rest of the array"
     * @param bool $preserve - Should keys be preserved, default no
     * @return array
     */
    public static function arraySlice(array $array, $start, $length = null, $preserve = NO)
    {
        trigger_error("Deprecated function; use Collection::slice instead", E_USER_NOTICE);
        return Collection::slice($array, $start, $length, $preserve);
    }

    /**
     * arrayReduce()
     * Reduce an array to a scalar value. Simple wrapper around the standard function
     * @param array $array
     * @param callable $callback
     * @param mixed $initial (optional)
     * @return mixed The reduced value
     */
    public static function arrayReduce(array $array, $callback, $initial = null)
    {
        trigger_error("Deprecated function; use Collection::reduce instead", E_USER_NOTICE);
        return Collection::reduce($array, $callback, $initial);
    }

    /**
     * arrayFilter()
     * Discard array elements, based upon a callback function. Does not maintain key-value association.
     * @param array $array
     * @param callable $callback, return truthy to keep
     * @return array
     */
    public static function arrayFilter(array $array, $callback)
    {
        trigger_error("Deprecated function; use Collection::filter instead", E_USER_NOTICE);
        return Collection::filter($array, $callback);
    }

    /**
     * inArray()
     * Does the needle exist in the haystack?
     * @param array $haystack
     * @param mixed $needle
     * @return boolean
     */
    public static function inArray(array $haystack, $needle)
    {
        trigger_error("Deprecated function; use Collection::has instead", E_USER_NOTICE);
        return Collection::has($haystack, $needle);
    }

    /**
     * arrayFlatten()
     * Flatten an array-of-arrays down into a single linear array.
     * Optional $deep parameter determines whether to recurse into sub arrays, or just do a single pass
     * @param array $array
     * @param boolean $deep (optional)
     * @return array
     */
    public static function arrayFlatten(array $array, $deep = NO)
    {
        trigger_error("Deprecated function; use Collection::flatten instead", E_USER_NOTICE);
        return Collection::flatten($array, $deep);
    }

    /**
     * arrayFind()
     * Find the first array element which matches the supplied callback
     * @param array $array
     * @param callable $callback
     * @return mixed matching element, or NULL for no match
     */
    public static function arrayFind(array $array, callable $callback)
    {
        trigger_error("Deprecated function; use Collection::find instead", E_USER_NOTICE);
        return Collection::find($array, $callback);
    }

    /**
     * arrayEvery()
     * Returns true if every element of the array matches a supplied callback
     * @param array $array
     * @param callable $callback
     * @return boolean
     */
    public static function arrayEvery(array $array, callable $callback)
    {
        trigger_error("Deprecated function; use Collection::every instead", E_USER_NOTICE);
        return Collection::every($array, $callback);
    }

    /**
     * arraySome()
     * Returns true if at least one element of the array matches a supplied callback
     * @param array $array
     * @param callable $callback
     * @return boolean
     */
    public static function arraySome(array $array, callable $callback)
    {
        trigger_error("Deprecated function; use Collection::some instead", E_USER_NOTICE);
        return Collection::some($array, $callback);
    }
}
