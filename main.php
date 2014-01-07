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
$router->addStaticRoute('/', 'YourCompany\YourProject\Presenters\Homepage::request');

// Identify how to route this request
$route = $router->route($_SERVER["DOCUMENT_URI"], $_SERVER["REQUEST_METHOD"]);

// If this request is not routable, call a 404
if (!is_callable($route["callable"])) {
    TDM\Escher\HTTP\StatusCode::returnCode(404);
    exit;
}

// Pass the request on to the Presenter
call_user_func_array($route["callable"], $route["arguments"]);
