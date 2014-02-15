<?php

/**
 * Your Project
 *
 * @copyright 2014 YourCompany
 * @package   \YourCompany\YourProject
 * @license   All Rights Reserved
 */

namespace YourCompany\YourProject\Presenters;

use \TDM\Escher as Escher;
use \YourCompany\YourProject as YourProject;

/**
 * Homepage
 *
 * Handles homepage requests
 *
 * @copyright 2014 Your Company
 * @author    You <you@example.com>
 */
class Homepage extends Escher\Presenter
{
    public static function request()
    {
        // Load the view
        $page = new YourProject\Views\Homepage();

        // Merge in data from the model
        // ...

        // Output the page
        echo $page->render();
    }
}
