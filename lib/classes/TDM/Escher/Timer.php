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
 * Timer
 *
 * A really simple page timer
 *
 * @author Scott Culverhouse <scott.culverhouse@twistdigital.co.uk>
 * @author Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright 2005-2013 Twist Digital Media
 */

class Timer
{
    private $start;
    private $hostname;

    public function __construct()
    {
        // Initialise the timings array
        $this->start = microtime(YES);

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
            microtime(YES) - $this->start,
            strlen($this->hostname) ? (' by ' . $this->hostname) : ''
        );
    }
}
