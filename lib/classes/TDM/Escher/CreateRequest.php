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
 * CreateRequest
 *
 * Make an HTTP request to some external resource
 *
 * @author    Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2005-2013 Twist Digital Media
 * @todo      Error handling is pretty spotty, could do better.
 * @todo      Better documentation
 */

class CreateRequest
{
    // Error codes
    const CANNOT_OPEN_SOCKET       = -10;
    const SOCKET_TIMED_OUT         = -20;
    const CONNECT_TIMED_OUT        = -21;
    const FAILED_WRITING_TO_SOCKET = -30;

    // HTTP line feed
    const CRLF = "\r\n";

    // Default options
    public $defaultHTTPVersion = 1.1;
    public $maxTimeoutRetries  = 3;
    public $defaultTimeout     = 30.0;
    public $defaultReadTimeout = 30.0;

    /**
     * get()
     * Simple wrapper that does a GET request
     */
    public function &get($url, Array $headers = [], Array $options = [])
    {
        return $this->fetch($url, 'GET', [], $headers, $options);
    }

    /**
     * head()
     * Simple wrapper that does a HEAD request
     */
    public function &head($url, Array $headers = [], Array $options = [])
    {
        return $this->fetch($url, 'HEAD', [], $headers, $options);
    }

    /**
     * delete()
     * Simple wrapper that does a DELETE request
     */
    public function &delete($url, Array $headers = [], Array $options = [])
    {
        return $this->fetch($url, 'DELETE', [], $headers, $options);
    }

    /**
     * put()
     * Simple wrapper that does a PUT request
     */
    public function &put($url, $data, Array $headers = [], Array $options = [])
    {
        return $this->fetch($url, 'PUT', $data, $headers, $options);
    }

    /**
     * post()
     * Simple wrapper that does a POST request
     */
    public function &post($url, $data = [], Array $headers = [], Array $options = [])
    {
        return $this->fetch($url, 'POST', $data, $headers, $options);
    }

    /**
     * createRequestString()
     * Builds an HTTP request string from a context object and a url
     */
    private function &createRequestString($url, $headers, $options)
    {
        // Merge the query string and path together, if required
        $fullpath = empty($url["query"]) ? $url["path"] : ($url["path"] . "?" . $url["query"]);

        // Add http basic authentication, if passed
        if (!empty($url["user"]) && !empty($url["pass"])) {
            $headers["Authorization"] = "Basic " . base64_encode($url["user"] . ":" . $user["pass"]);
        }

        // Add in a host header if one was not set
        if (empty($headers["Host"])) {
            $headers["Host"] = $url["host"];
        }

        // Create the initial request string
        $request = sprintf(
            "%s %s HTTP/%.1f" . self::CRLF,
            strtoupper($options["method"]),
            $fullpath,
            $options["protocol_version"]
        );

        // Add in the headers
        foreach ($headers as $key => $value) {
            $request .= $key . ": " . $value . self::CRLF;
        }
        $request .= self::CRLF;

        // If there is data to add in, also add data
        if (!empty($options["content"])) {
            $request .= $options["content"] . self::CRLF;
        }

        // Return the finished string
        return $request;
    }

    private function readChunkedDataFromSocket($socket)
    {
        // Read chunk lengths from the data stream
        $buffer = tmpfile();
        while ($length = fgets($socket, 128)) {

            // Lengths may contain a ; terminator for chunk extensions.
            $length = substr($length, 0, strpos($length, ";") ?: -2);

            // If the length is 0 bytes then we're done. Read the last
            // CRLF from the socket and then quit.
            if ($length === "0") {
                fseek($socket, 2, SEEK_CUR);
                break;
            }

            // Otherwise, convert the length from hex to dec and read onto the buffer
            fwrite($buffer, stream_get_contents($socket, hexdec($length)));

            // Seek past the CRLF
            fseek($socket, 2, SEEK_CUR);
        }

        // Seek to the start of the buffer and return the data
        rewind($buffer);
        return stream_get_contents($buffer);
    }

    private function &connect($specification, $connectionTimeout = null)
    {
        // Check for a cached connection
        static $connections = [];
        if (isset($connections[$specification]) && is_resource($connections[$specification])) {

            // There is a connection! But it might be dead. Check.
            $socketMetaData    = @stream_get_meta_data($connections[$specification]);
            $socketHasTimedOut = !empty($socketMetaData['timed_out']);
            $socketIsEOF       = !empty($socketMetaData['eof']);

            // Return a socket if we have one
            if (!$socketHasTimedOut && !$socketIsEOF) {
                return $connections[$specification];
            }
        }

        // Establish a new connection
        $connections[$specification] = @stream_socket_client(
            $specification,
            $errorNumber,
            $errorString,
            $connectionTimeout
        );

        if (!$connections[$specification]) {
            sleep(3);
            return $this->connect($specification, $connectionTimeout);
        }

        return $connections[$specification];
    }

    /**
     * fetch()
     * Actually does the request.
     * "But we did all the work!" </itcrowd>
     */
    private function &fetch($url, $method, $data, Array $customHeaders, Array $options)
    {
        // Merge the default and passed headers
        $headers = array_merge([], $customHeaders);

        // Include passed data, if set
        if (sizeof($data)) {

            if ($method === 'POST' && empty($headers['Content-Type'])) {
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            }

            if (is_array($data)) {
                if ($headers["Content-Type"] === "application/json") {
                    $data = json_encode($data);
                } elseif ($headers["Content-Type"] === 'application/x-www-form-urlencoded') {
                    $data = http_build_query($data);
                }
            }

            $options['content'] = $data;
            $headers['Content-Length']  = strlen($data);
        }

        // Setup the default options
        $defaultOptions = array(
            'protocol_version' => $this->defaultHTTPVersion,
            'method'           => strtoupper($method),
            'timeout'          => $this->defaultTimeout,
            'read_timeout'     => $this->defaultReadTimeout,
            'ignore_errors'    => true,
            'max_retries'      => $this->maxTimeoutRetries,
        );

        // Combine with other context options
        $options = array_merge($defaultOptions, $options);

        // Allow the user to pass a parse_url()-style array, or a string as the URL
        if (is_scalar($url)) {
            $url = parse_url($url);
            if ($url["scheme"] === "https" && empty($url["port"])) {
                $url["port"] = 443;
            }
        }

        // Merge in defaults to ensure we always have a complete array
        $url = array_merge(array(
            "scheme"   => "http",
            "host"     => "localhost",
            "port"     => 80,
            "user"     => null,
            "pass"     => null,
            "path"     => "/",
            "query"    => "",
            "fragment" => "",
        ), $url);

        // Create an HTTP request
        $socketSpecification = sprintf(
            '%s://%s:%d',
            $url["scheme"] === "https" ? "ssl" : "tcp",
            $url["host"],
            $url["port"]
        );

        // Connect to the remote server
        $socket = $this->connect($socketSpecification, $options["timeout"]);
        stream_set_timeout($socket, $options["read_timeout"]);

        // Build the request string
        $requestString = $this->createRequestString($url, $headers, $options);

        // Write to the socket
        $bytesWritten = fwrite($socket, $requestString);
        if ($bytesWritten !== strlen($requestString)) {

            // We failed to write to this socket, so kill it.
            fclose($socket);

            // Return an error
            $errorCode = self::FAILED_WRITING_TO_SOCKET;
            return $errorCode;
        }

        // Try to read the headers off the socket.
        // We keep a copy of them raw to return to the client, and a parsed set for our own use.
        $headers = [];
        $rawHeaders = "";

        while ($line = @stream_get_line($socket, 1024, "\r\n")) {
            $rawHeaders .= $line . self::CRLF;
            if (false === strpos($line, ":")) {
                $headers["status"] = $line;
            } else {
                list ($header, $value) = explode(": ", $line, 2);
                $headers[strtolower($header)] = $value;
            }
        }

        // If we failed to get any headers, then the socket has failed.
        if (empty($headers)) {
            return self::SOCKET_TIMED_OUT;
        }

        // Check for chunked transfer encoding and decode
        if (isset($headers["transfer-encoding"]) && $headers["transfer-encoding"] === "chunked") {
            $body = $this->readChunkedDataFromSocket($socket);
        } elseif (isset($headers["content-length"])) {
            $body = stream_get_contents($socket, $headers["content-length"]);
        } else {
            $body = '';
        }

        // Check for redirects
        if (preg_match('#^HTTP/1.[01] 30[123]#i', $headers["status"]) && !empty($options["follow_location"])) {

            // In the event of a redirect, seek a location header
            if (isset($headers["location"])) {
                $url = array_merge($url, parse_url($headers["location"]));
                return $this->get($url, $customHeaders);
            }
        }

        // If the socket has died during this request then kill it
        if (feof($socket)) {
            fclose($socket);
        }

        // Return the decoded data
        $reply = $rawHeaders . self::CRLF . $body;
        return $reply;
    }
}
