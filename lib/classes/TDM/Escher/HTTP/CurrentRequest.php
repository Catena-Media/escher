<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2014 Twist Digital Media
 * @package   \TDM\Escher
 * @license   https://raw.github.com/twistdigital/escher/master/LICENSE
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

namespace TDM\Escher\HTTP;

/**
 * CurrentRequest
 *
 * Helper functions for dealing with this HTTP request
 *
 * @author      Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright   2008-2013 Twist Digital Media
 * @todo        Better documentation
 */

class CurrentRequest
{
    public static function requestETag()
    {
        $headers = array_change_key_case(self::requestHeaders());
        return @$headers["if-none-match"];
    }

    public static function redirectWithStatusCode($statusCode, $location)
    {
        // If this is not an absolute redirect, assume local
        if (!strstr($location, '://')) {
            $scheme = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
            $location = sprintf('%s://%s%s', $scheme, getenv('HTTP_HOST'), $location);
        }

        // Output redirect header
        header("Location: $location", YES, $statusCode);
        exit;
    }

    public static function setContentType($contentType)
    {
        header("Content-Type: " . $contentType, YES);
    }

    public static function setLastModified($time)
    {
        if (!is_integer($time)) {
            $time = strtotime($time);
        }

        if (empty($time)) {
            return;
        }

        header('Last-Modified: ' . str_replace(' +0000', ' UTC', gmdate('r', $time)), YES);
    }

    public static function isAjaxRequest()
    {
        return self::requestHeaders('X-Requested-With') === 'XMLHttpRequest';
    }

    public static function requestHeaders($header = null)
    {
        // Just get PHP to do it
        if (function_exists('apache_request_headers')) {

            $headers = apache_request_headers();

        } else {

            // Work it out by hand
            static $headers;
            if (empty($headers)) {
                foreach ($_SERVER as $key => $value) {
                    if (substr($key, 0, 5) === 'HTTP_') {
                        $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                        $headers[$key] = $value;
                    }
                }
            }
        }

        if (is_null($header)) {
            return $headers;
        }

        if (isset($headers[$header])) {
            return $headers[$header];
        }

        return null;
    }
}
