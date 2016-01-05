<?php

/**
 * Your Project
 * @copyright YourCompany
 * @package \YourCompany\YourProject
 */

namespace YourCompany\YourProject\Views;

use \TDM\Escher;

/**
 * Renders the common site elements
 * @author You
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
     * @return string - The namespace of the created view
     */
    protected function loadView()
    {
        // This content should be minified by default
        $this->template->addProcessor(['\TDM\Escher\HTMLMinify', 'minify']);

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
     * @param array $meta - An array of meta data, expect ["title" => "...," "description" => "..."]
     * @return void
     */
    public function setMetaData(array $meta)
    {
        $this->template->assign($meta, "Main:Meta");
    }
}
