<?php

/**
 * Escher Framework
 * @copyright Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * View
 * The View base class
 * @author Mike Hall
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
