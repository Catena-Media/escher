<?php

/**
 * Your Project
 *
 * @copyright 2015 YourCompany
 * @package \YourCompany\YourProject
 * @license All Rights Reserved
 */

namespace YourCompany\YourProject\Presenters;

use \TDM\Escher as Escher;
use \YourCompany\YourProject as YourProject;

/**
 * Homepage
 *
 * Handles requests for the homepage
 *
 * @copyright 2015 Your Company
 * @author You <you@example.com>
 */
class Homepage extends Escher\Presenter
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
