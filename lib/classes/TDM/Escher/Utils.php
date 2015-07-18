<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2015 Twist Digital Media
 * @package \TDM\Escher
 * @license https://raw.github.com/twistdigital/escher/master/LICENSE
 *
 * Copyright (c) 2000-2014, Twist Digital Media
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *    list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this
 *    list of conditions and the following disclaimer in the documentation and/or
 *    other materials provided with the distribution.
 *
 * 3. Neither the name of the {organization} nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
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
     * @param callable $callback, return true to keep
     * @return array
     */
    public static function arrayFilter(Array $array, $callback)
    {
        $array = array_filter($array, $callback);
        sort($array);
        return $array;
    }
}
