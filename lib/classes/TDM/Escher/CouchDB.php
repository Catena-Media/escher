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
 * CouchDB
 *
 * @author Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright Twist Digital Media 2013
 * @todo A ground-up rewrite, as this code is shocking
 */

class CouchDB extends Singleton
{
    private $http;
    private $baseUrl;

    protected function __construct($base = null)
    {
        $this->http = new CreateRequest();
        $this->baseUrl = $base ?: (new Settings())->couchdb["host"];
    }

    private function parseHeaders($raw)
    {
        $headers = array();
        foreach ($raw as $header) {
            list($field, $value) = explode(': ', $header);
            $headers[$field] = $value;
        }

        return $headers;
    }

    private function parseStatus($raw)
    {
        if (preg_match('/^HTTP\/1\.[01] ([0-9]{3}) /', $raw, $match)) {
            return $match[1];
        }
        return 500;
    }

    private function handleReply($reply)
    {
        // Check for errors
        if (is_numeric($reply) && $reply < 0) {
            switch ($reply) {
                case CreateRequest::SOCKET_TIMED_OUT:
                    $statusCode = 504;
                    break;
                case CreateRequest::CANNOT_OPEN_SOCKET:
                    $statusCode = 503;
                    break;
                default:
                    $statusCode = 500;
                    break;
            }

            // Default variables
            $headers = [];
            $body    = null;

        } else {

            // break into headers/body
            @list($headers, $body) = explode("\r\n\r\n", $reply, 2);

            // Split the headers
            $headers = explode("\r\n", $headers);

            // Decode the body
            $body = json_decode($body, YES);

            // Parse out the status code
            $statusCode = $this->parseStatus(array_shift($headers));
        }

        // Process the remaining headers
        $headers = array_merge(
            array(
                'Status' => $statusCode,
            ),
            $this->parseHeaders($headers)
        );

        // Format into a reply object
        $reply = array(
            'status'  => ($statusCode{0} == 2) ? 'ok' : 'fail',
            'headers' => $headers,
            'body'    => isset($body['rows']) ? $body['rows'] : $body,
        );

        // Return
        return $reply;
    }

    public static function get($resource, Array $headers = [], Array $options = [])
    {
        // Get an instance
        $couch = self::instance();

        // Define the URL for the request
        $url = $couch->baseUrl . $resource;

        // Merge in default options
        $options = array_merge(
            [
                'max_redirects' => 0,
            ],
            $options
        );

        // Process and return
        $reply = $couch->http->get($url, $headers, $options);

        return $couch->handleReply($reply);
    }

    public static function view($resource, $design, $view, $params = [], Array $headers = [], Array $options = [])
    {
        // Get an instance
        $couch = self::instance();

        // Define the URL for the request
        $url = $couch->baseUrl . $resource . "/_design/" . urlencode($design) . "/_view/" . urlencode($view);
        $url .= '?' . http_build_query(array_map("json_encode", $params));

        // Merge in default options
        $options = array_merge(
            [
                'max_redirects' => 0,
            ],
            $options
        );

        // Process and return
        $reply = $couch->http->get($url, $headers, $options);
        return $couch->handleReply($reply);
    }

    public static function delete($resource, Array $headers = [], Array $options = [])
    {
        // Get an instance
        $couch = self::instance();

        // Define the URL for the request
        $url = $couch->baseUrl . $resource;

        // Merge in default options
        $options = array_merge(
            [
                'max_redirects' => 0,
            ],
            $options
        );

        // Process and return
        $reply = $couch->http->delete($url, $headers, $options);

        return $couch->handleReply($reply);
    }

    public static function post($resource, $data = [], Array $headers = [], Array $options = [])
    {
        // Get an instance
        $couch = self::instance();

        // Define the URL for the request
        $url = $couch->baseUrl . $resource;

        // If the data is an array, JSON-encode it
        if (!is_scalar($data)) {
            $data = json_encode($data);
            $headers['Content-Type'] = 'application/json';
        }

        // Merge in default options
        $options = array_merge(
            [
                'max_redirects' => 0,
            ],
            $options
        );

        // Process and return
        $reply = $couch->http->post($url, $data, $headers, $options);

        return $couch->handleReply($reply);
    }

    public static function put($resource, $data = [], Array $headers = [], Array $options = [])
    {
        // Get an instance
        $couch = self::instance();

        // Define the URL for the request
        $url = $couch->baseUrl . $resource;

        // If the data is an array, JSON-encode it
        if (!is_scalar($data)) {
            $data = json_encode($data);
            $headers['Content-Type'] = 'application/json';
        }

        // Merge in default options
        $options = array_merge(
            [
                'max_redirects' => 0,
            ],
            $options
        );

        // Process and return
        $reply = $couch->http->put($url, $data, $headers, $options);

        return $couch->handleReply($reply);
    }

    public static function cleanupDocument($document)
    {
        unset($document["_id"], $document["_rev"], $document["type"]);
        return $document;
    }

    public static function cleanupView($documents)
    {
        return array_map(
            function ($document) {

                // If this is an include docs view, only look at the document
                if (isset($document["doc"])) {
                    $document = $document["doc"];
                }

                // Clean up the CouchDB meta data
                return self::cleanupDocument($document);

            },
            $documents
        );
    }
}
