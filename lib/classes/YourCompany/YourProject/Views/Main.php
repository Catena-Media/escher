<?php

/**
 * Your Project
 *
 * @copyright 2014 YourCompany
 * @package   \YourCompany\YourProject
 * @license   All Rights Reserved
 */

namespace YourCompany\YourProject\Views;

use \TDM\Escher as Escher;

/**
 * Homepage
 *
 * Displays the homepage
 *
 * @copyright 2014 Your Company
 * @author    You <you@example.com>
 */

class Main extends Escher\View
{
    protected function loadView()
    {
        $this->template->loadTemplate("templates/main.html", "Main");
        $this->template->addProcessor(['\TDM\Escher\Minify\HTML', 'minify']);
        return "Main";
    }

    public function render($namespace = "Main")
    {
        return parent::render($namespace);
    }
}
