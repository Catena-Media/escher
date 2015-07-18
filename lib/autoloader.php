<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2014 Twist Digital Media
 * @package   \TDM\Escher
 * @license   https://raw.github.com/twistdigital/escher/master/LICENSE
 */

/**
 * PSR-0 Compatible Class Autoloader
 *
 * @author Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2005-2012 Twist Digital Media
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
