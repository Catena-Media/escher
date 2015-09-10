<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2015 Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * View
 *
 * The View base class
 *
 * @author Mike Hall
 * @copyright 2014 Digital Design Labs Ltd
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

    protected function shouldRenderOutput()
    {
        return YES;
    }

    protected function willRenderOutput()
    {
        // Noop
    }

    public function render($namespace = "Main")
    {
        if ($this->shouldRenderOutput() === NO) {
            return "";
        }

        $this->willRenderOutput();
        return $this->template->render($namespace);
    }
}
