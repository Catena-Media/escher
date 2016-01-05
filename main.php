<?php

/**
 * Your Project
 * @copyright YourCompany
 * @package \YourCompany\YourProject
 */

// Load Escher
require "escher.php";

// If the URI finishes with a /, redirect to one without - unless its the root
if ($_SERVER["DOCUMENT_URI"] !== "/" && substr($_SERVER["DOCUMENT_URI"], -1) === "/") {
    $redirectTo = implode("?", array_filter(array(rtrim($_SERVER["DOCUMENT_URI"], "/"), $_SERVER["QUERY_STRING"])));
    TDM\Escher\CurrentRequest::redirectWithStatusCode(301, $redirectTo);
}

// Set up the routing table
$router = new TDM\Escher\Router();
$router->map('/', 'YourCompany\YourProject\Presenters\Homepage::request');

// Identify how to route this request
$route = $router->route($_SERVER["REQUEST_METHOD"], $_SERVER["DOCUMENT_URI"]);

// If this request is not routable, call a 404
if (!is_callable($route)) {
    TDM\Escher\CurrentRequest::returnCode(404);
}

try {
    // Route down to a presenter
    echo call_user_func($route);
} catch (\Exception $ignore) {
    TDM\Escher\CurrentRequest::returnCode(500);
}
