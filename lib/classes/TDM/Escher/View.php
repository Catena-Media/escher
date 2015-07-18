<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2014 Twist Digital Media
 * @package \TDM\Escher
 * @license https://raw.github.com/twistdigital/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * View
 *
 * The View base class
 *
 * @author Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2014 Twist Digital Media
 * @todo Better documentation
 */

abstract class View
{
    public $template;
    protected $namespace;

    public function __construct()
    {
        $this->template = Template::instance();
        $this->loadView();
    }

    protected function loadView()
    {
        // Noop
    }

    public function render($namespace = "Main")
    {
        return $this->template->render($namespace);
    }
}
