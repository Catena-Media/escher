<?php

// @codingStandardsIgnoreFile

/**
 * Escher Framework
 * @copyright Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

/**
 * YES and NO constants.
 * I nicked these from Objective-C, because I like the readability.
 */
define("YES", true);
define("NO", false);

/**
 * @const string The root directory for Escher
 */
define("ROOTDIR", __DIR__);

// If there is a local configuraton file defined for this project
// (which there usually should be) then include it.
if (is_readable(ROOTDIR . "/localconfig.php")) {
    include ROOTDIR . "/localconfig.php";
}

// If this project uses composer, include the composer autoloader
if (is_readable(ROOTDIR . "/vendor/autoload.php")) {
    require ROOTDIR . "/vendor/autoload.php";
}
