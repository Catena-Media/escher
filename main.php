<?php

/**
 * Your Project
 * @copyright YourCompany
 * @package \YourCompany\YourProject
 */

// Load Escher
require "escher.php";

use \TDM\Escher\Router;
use \TDM\Escher\CurrentRequest;

// If the URI finishes with a /, redirect to one without - unless its the root
if ($_SERVER["DOCUMENT_URI"] !== "/" && substr($_SERVER["DOCUMENT_URI"], -1) === "/") {
    $redirectTo = implode("?", array_filter(array(rtrim($_SERVER["DOCUMENT_URI"], "/"), $_SERVER["QUERY_STRING"])));
    CurrentRequest::redirectWithStatusCode(301, $redirectTo);
}

// Set up the routing table
$router = new Router();
$router->map('/', 'YourCompany\YourProject\Controllers\Homepage::request');

// Identify how to route this request
$route = $router->route($_SERVER["REQUEST_METHOD"], $_SERVER["DOCUMENT_URI"]);

// If this request is not routable, call a 404
if (is_callable($route) === YES) {
    try {
        echo call_user_func($route);
    } catch (\Exception $ignore) {
        CurrentRequest::returnCode(500);
    }
} else {
    CurrentRequest::returnCode(404);
}
