<?php

/**
 * Your Project
 * @copyright YourCompany
 * @package \YourCompany\YourProject
 */

namespace YourCompany\YourProject\Controllers;

use \TDM\Escher;
use \YourCompany\YourProject;

/**
 * Homepage
 * Handles requests for the homepage
 * @author You
 */
class Homepage extends Escher\Controller
{
    public static function request()
    {
        // Load the view
        $page = new YourProject\Views\Homepage();

        // Merge in data from the model
        // ...

        $page->setMetaData(array(
            "title" => "My Awesome Website",
            "description" => "Things you can do on my awesome website",
        ));

        // Output the page
        return $page->render();
    }
}
