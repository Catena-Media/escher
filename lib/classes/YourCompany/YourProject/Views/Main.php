<?php

/**
 * Your Project
 *
 * @copyright 2015 YourCompany
 * @package \YourCompany\YourProject
 * @license All Rights Reserved
 */

namespace YourCompany\YourProject\Views;

use \TDM\Escher as Escher;

/**
 * Renders the common site elements
 *
 * @copyright 2015 Your Company
 * @author You <you@example.com>
 */
class Main extends Escher\View
{
    /**
     * Store the namespace of this view
     * @var string
     * @access protected
     */
    protected $namespace;

    /**
     * Load and set up the view
     *
     * @return string - The namespace of the created view
     */
    protected function loadView()
    {
        // This content should be minified by default
        $this->template->addProcessor(['\TDM\Escher\Minify\HTML', 'minify']);

        // Load the template file
        $this->namespace = $this->template->loadTemplate(ROOTDIR . "/templates/main.html", "Main");

        // Load the cache busters
        $this->template->assign(array(
            "scriptsLastModified" => filemtime(ROOTDIR . "/public/javascript.js"),
            "stylesLastModified" => filemtime(ROOTDIR . "/public/stylesheet.css"),
        ), $this->namespace);

        return $this->namespace;
    }

    /**
     * Set the meta data for this page
     *
     * @param array $meta - An array of meta data, expect ["title" => "...," "description" => "..."]
     * @return void
     */
    public function setMetaData(Array $meta)
    {
        $this->template->assign($meta, "Main:Meta");
    }
}
