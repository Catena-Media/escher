<?php

/**
 * Your Project
 *
 * @copyright 2014 YourCompany
 * @package   \YourCompany\YourProject
 * @license   All Rights Reserved
 */

namespace YourCompany\YourProject\Views;

/**
 * Homepage
 *
 * Displays the homepage
 *
 * @copyright 2014 Your Company
 * @author    You <you@example.com>
 */

class Homepage extends Main
{
    protected function loadView()
    {
        $parent = parent::loadView();
        $this->template->loadTemplate("templates/homepage.html", "$parent:Content");
        return "$parent:Content";
    }
}
