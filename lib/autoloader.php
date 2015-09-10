<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2015 Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

/**
 * PSR-0 Compatible Class Autoloader
 *
 * @author Mike Hall
 * @copyright 2005-2012 Digital Design Labs Ltd
 * @param string $className - The class to load
 * @return void
 */
spl_autoload_register(function ($className) {

    // Lose the leading slash
    $className = ltrim($className, '\\');

    // Derive the path from the class name
    $broken = explode('\\', $className);
    $sourceFilename = str_replace('_', DIRECTORY_SEPARATOR, array_pop($broken)) . '.php';
    $sourcePath     = implode(DIRECTORY_SEPARATOR, $broken);

    // Raw classes (without namespace support) live in a folder called classes
    if (empty($sourcePath)) {
        $check = ROOTDIR . '/lib/classes/' . $sourceFilename;
    } else {
        $check = ROOTDIR . '/lib/classes/' . $sourcePath . '/' . $sourceFilename;
    }

    // Include the file. We're done.
    if (is_readable($check)) {
        include $check;
    }
});
