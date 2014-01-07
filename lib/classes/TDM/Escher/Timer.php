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

namespace TDM\Escher;

/**
 * Timer
 *
 * A really simple page timer
 *
 * @author      Scott Culverhouse <scott.culverhouse@twistdigital.co.uk>
 * @author      Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright   2005-2013 Twist Digital Media
 */

class Timer
{
    private $start;
    private $hostname;

    public function __construct($logpath = false)
    {
        // Initialise the timings array
        $this->start = microtime(true);

        // Get the hostname
        $host = strtoupper(gethostname());

        // We just want the first part
        $host = explode('.', $host);
        $this->hostname = $host[0];
    }

    public function getString()
    {
        return sprintf(
            'Served in %2.3fs%s',
            microtime(true) - $this->start,
            strlen($this->hostname) ? (' by ' . $this->hostname) : ''
        );
    }
}
