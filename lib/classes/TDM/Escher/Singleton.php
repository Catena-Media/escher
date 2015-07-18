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
 * Singleton
 *
 * Abstract class for creating singletons. Extend a class from this
 * abstract class, and then use $instance = YourClass::instance() to get
 * a singleton instance of that class.
 *
 * @author Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2014 Twist Digital Media
 */

abstract class Singleton
{
    /**
     * Escher\Singleton::instance()
     *
     * @param boolean $new - Get a new instance, not a singleton
     * @return object - The instance
     **/
    public static function &instance($new = NO)
    {
        // Get the name of this class
        $className = get_called_class();

        if ($new) {
            return new $className();
        }

        static $instance;
        if ($instance instanceof $className) {
            return $instance;
        }

        // Create a fresh template
        $instance = new $className();
        return $instance;
    }
}
