<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2014 Twist Digital Media
 * @package \TDM\Escher
 * @license https://raw.github.com/twistdigital/escher/master/LICENSE
 *
 * Copyright (c) 2000-2014, Twist Digital Media
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *    list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this
 *    list of conditions and the following disclaimer in the documentation and/or
 *    other materials provided with the distribution.
 *
 * 3. Neither the name of the {organization} nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace TDM\Escher;

/**
 * Console
 *
 * Handles requests coming from the console
 * TODO: Make this not terrible.
 *
 * @author Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2014 Twist Digital Media
 */

class Console
{
    public static function createPresenter($args)
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

        $template->loadTemplate(ROOTDIR . "/templates/.escher/presenter.class", "Class");

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
        $filename = ROOTDIR . "/lib/classes/" . implode("/", [$vendor, $package, "Presenters", $className]) . ".php";
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
