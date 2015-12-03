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
 * For error handling
 * @author Mike Hall
 * @copyright 2014 Digital Design Labs Ltd
 */

class Error extends \Exception
{
    /**
     * Meta data about this error
     * @var array
     * @access public
     */
    public $meta;

    /**
     * New Error Constructor
     * @param string $message - Error message
     * @param array $meta - Meta data about this error
     */
    public function __construct($message, array $meta = [])
    {
        $this->meta = $meta;
        parent::__construct($message);
    }

    /**
     * Tests if an object is an error
     * @param mixed $o - The variable to test
     * @return boolean - YES if this is an error
     */
    public static function isError($o)
    {
        return $o instanceof Error;
    }
}
