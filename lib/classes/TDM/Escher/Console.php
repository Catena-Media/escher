<?php

/**
 * Escher Framework
 * @copyright Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * Console
 * Handles requests coming from the console
 * @todo Make this not terrible.
 * @author Mike Hall
 */
class Console
{
    public static function createController($args)
    {
        $validName = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

        if (empty($args[0]) || !preg_match($validName, $args[0])) {
            trigger_error("Expected a valid vendor name", E_USER_ERROR);
        }

        if (empty($args[1]) || !preg_match($validName, $args[1])) {
            trigger_error("Expected a valid package name", E_USER_ERROR);
        }

        if (empty($args[2]) || !preg_match($validName, $args[2])) {
            trigger_error("Expected a valid class name", E_USER_ERROR);
        }

        list ($vendor, $package, $className) = array_slice($args, 0, 3);

        $template = Template::instance();

        $template->loadTemplate(ROOTDIR . "/templates/.escher/controller.class", "Class");

        $template->assign(array(
            "Vendor" => $vendor,
            "Package" => $package,
            "ClassName" => $className,
            "ProjectName" => $package,
            "VendorName" => $vendor,
            "YourName" => "You <you@example.com>",
            "Date" => date("Y"),
        ), "Class");

        // Where should this file go?
        $filename = ROOTDIR . "/lib/classes/" . implode("/", [$vendor, $package, "Controllers", $className]) . ".php";
        $dirname  = dirname($filename);
        if (!is_dir($dirname)) {
            mkdir($dirname, null, YES);
        }

        if (!is_writable($dirname)) {
            trigger_error("Cannot write to " . $dirname, E_USER_ERROR);
        }

        if (file_exists($filename)) {
            trigger_error("File already exists at " . $filename, E_USER_ERROR);
        }

        file_put_contents($filename, $template->render("Class"));
    }
}
