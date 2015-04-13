<?php

/**
 * Your Project
 *
 * @copyright 2014 YourCompany
 * @package   \YourCompany\YourProject
 * @license   All Rights Reserved
 */

// Load Escher
require "escher.php";

// Instantiate a router
$router = new TDM\Escher\Router();

// Set up the routing table
$router->map('/', 'YourCompany\YourProject\Presenters\Homepage::request');

// Identify how to route this request
$route = $router->route($_SERVER["REQUEST_METHOD"], $_SERVER["DOCUMENT_URI"]);

// Route down to a presenter
if (is_callable($route)) {
    echo call_user_func($route);

} else {
    // If this request is not routable, call a 404
    TDM\Escher\HTTP\StatusCode::returnCode(404);
}
