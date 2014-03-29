<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2014 Twist Digital Media
 * @package   \TDM\Escher
 * @license   https://raw.github.com/twistdigital/escher/master/LICENSE
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

/**
 * PSR-0 Compatible Class Autoloader
 *
 * @author    Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2005-2012 Twist Digital Media
 * @param     string $className The class to load
 * @return    void
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
