<?php

/**
 * Escher Framework
 * @copyright Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

/**
 * PSR-4 Compatible Class Autoloader
 * @author Mike Hall
 * @author Nathan Pace
 * @param string $className - The class to load
 * @return void
 */
spl_autoload_register(function ($className) {

    // Lose the leading slash
    $className = ltrim($className, "\\");

    // Derive the path from the class name
    $broken = explode("\\", $className);
    $sourceFilename = array_pop($broken) . ".php";
    $sourcePath = implode(DIRECTORY_SEPARATOR, $broken);

    // Include the file. We're done.
    $check = ROOTDIR . "/lib/class/{$sourcePath}/{$sourceFilename}";
    if (is_readable($check)) {
        include $check;
    }
});
