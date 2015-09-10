<?php

/**
 * Your Project
 *
 * @copyright 2015 YourCompany
 * @package \YourCompany\YourProject
 */

namespace YourCompany\YourProject\Views;

/**
 * Displays the homepage
 *
 * @copyright 2015 Your Company
 * @author You
 */
class Homepage extends Main
{
    /**
     * Load the homepage view
     *
     * @return string - The namespace of the view
     */
    protected function loadView()
    {
        // Load the parent view
        $parent = parent::loadView();

        // Load the homepage in as the content view
        $this->namespace = $this->template->loadTemplate(ROOTDIR . "/templates/homepage.html", "{$parent}:Content");

        // Return the namespace
        return $this->namespace;
    }
}
