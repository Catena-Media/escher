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

namespace TDM\Escher;

/**
 * Router
 *
 * A really simple router
 *
 * @author      Claire Patterson <claire.patterson@twistdigital.co.uk>
 * @author      Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright   2014 Twist Digital Media
 */

class Router
{
    // this is where the route maps live
    public $staticRoutes   = [];
    public $wildcardRoutes = [];

    private function normalizeUrl($url)
    {
        return '/' . strtolower(trim($url, '/'));
    }

    private function normalizeMethod($method)
    {
        $method = strtoupper($method);
        if ($method !== "POST") {
            return "GET";
        }
        return "POST";
    }

    private function map($url, $route, &$table)
    {
        // Normalize the URL
        $url = $this->normalizeUrl($url);

        // If this is a simple route, store it
        if (is_callable($route, YES)) {
            $table[$url] = $route;
            return;
        }

        // If this is a complex route, validate it and store it
        if (is_array($route)) {
            foreach ($route as $method => $path) {
                $method = $this->normalizeMethod($method);
                if (is_callable($path, YES)) {
                    $table[$url][$method] = $path;
                }
            }
            return;
        }
    }

    public function addStaticRoute($url, $route)
    {
        return $this->map($url, $route, $this->staticRoutes);
    }

    public function addWildcardRoute($url, $route)
    {
        return $this->map($url, $route, $this->wildcardRoutes);
    }

    private function unmap($url, $method, &$table)
    {
        $url = $this->normalizeUrl($url);
        if (isset($table[$url])) {

            // Simple routes, or empty methods, just removes the lot
            if (is_scalar($table[$url]) || is_null($method)) {
                unset($table[$url]);
                return;
            }

            // Just remove this method
            $method = $this->normalizeMethod($method);
            if (isset($table[$url][$method])) {
                unset($table[$url][$method]);
                return;
            }
        }
    }

    public function removeStaticRoute($url, $method = null)
    {
        return $this->unmap($url, $method, $this->staticRoutes);
    }

    public function removeWildcardRoute($url, $method = null)
    {
        return $this->unmap($url, $method, $this->wildcardRoutes);
    }

    public function route($url, $method = "GET")
    {
        // get request method
        $method = $this->normalizeMethod($method);

        // normalize the url
        $url = $this->normalizeUrl($url);
        return $this->match($url, $method);
    }

    private function match($url, $method)
    {
        // check for a direct match in route table
        if (isset($this->staticRoutes[$url])) {

            // if there is a single entry and it matches the route table just return
            if (is_scalar($this->staticRoutes[$url])) {
                return array(
                    "callable"  => $this->staticRoutes[$url],
                    "arguments" => [],
                );
            }

            // check if there is a specific route for this method
            if (isset($this->staticRoutes[$url][$method])) {
                return array(
                    "callable"  => $this->staticRoutes[$url][$method],
                    "arguments" => [],
                );
            }

            // nothing matched? nothing to give you
            return ["callable" => null, "arguments" => []];
        }

        // we didn't find a direct match
        // check for routes with variables
        foreach ($this->wildcardRoutes as $wildcard => $route) {

            // Convert wild-cards to regex
            $regex = strtr($wildcard, array(
                ':any'   => '[^/]+',
                ':num'   => '[0-9]+',
                ':nonum' => '[^0-9]+',
                ':alpha' => '[A-Za-z]+',
                ':alnum' => '[A-Za-z0-9]+',
                ':hex'   => '[A-Fa-f0-9]+',
            ));

            // Does the RegEx match?
            if (preg_match('#^' . $regex . '$#', $url, $matches)) {

                // take off the full match
                array_shift($matches);

                // If this is a simple route, just return it
                if (is_scalar($route)) {
                    return array(
                        "callable" => $route,
                        "arguments" => $matches,
                    );
                }

                // Otherwise, check the method
                if (isset($route[$method])) {
                    return array(
                        "callable"  => $route[$method],
                        "arguments" => $matches,
                    );
                }

                // Route matched, but method did not.
                return array(
                    "callable" => function ($status) {
                        HTTP\StatusCode::returnCode($status);
                        exit;
                    },
                    "arguments" => [405],
                );
            }
        }
    }
}
