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
 * ProcessManager
 *
 * A Process Manager for PHP batch scripts, with support for locking and logging
 *
 * @author      Mike Hall <mike.hall@twistdigital.co.uk>
 * @copyright   2005-2014 Twist Digital Media
 * @todo        Needs a complete overall, makes some unwarranted assumptions about writable paths
 */

class ProcessManager
{
    /**
     * Contains the path to the lock file
     *
     * @var     string
     * @access  private
     */
    private static $lockFilePath = null;

    /**
     * Contains the path to the log file
     *
     * @var     string
     * @access  private
     */
    private static $logFilePath = null;

    /**
     * Contains an instance of this object
     *
     * @var     object
     * @access  private
     */
    private static $processHandler = null;

    /**
     * Should I echo log messages?
     *
     * @var     bool
     * @access  public
     */
    public static $verbose = false;

    /**
     * Constructor
     *
     * @return  void
     * @access  private
     * @ignore
     */
    private function __construct()
    {
        self::$lockFilePath = 'locks/' . strtolower(basename($_SERVER['PHP_SELF'], '.php')) . '.lock';
        self::$logFilePath  = 'logs/'  . strtolower(basename($_SERVER['PHP_SELF'], '.php')) . '.log';
    }

    /**
     * Lock current script
     *
     * @return  bool    TRUE on successful lock, FALSE on error
     * @access  private
     */
    private static function realLock()
    {
        clearstatcache();
        if (!file_exists(self::$lockFilePath)) {
            return file_put_contents(self::$lockFilePath, getmypid());
        }
        return false;
    }

    /**
     * Unlock current script
     *
     * Removes the current lock file, if it was created by this process
     *
     * @access  private
     */
    private static function realUnlock()
    {
        clearstatcache();
        if (file_exists(self::$lockFilePath) && trim(file_get_contents(self::$lockFilePath)) == getmypid()) {
            @unlink(self::$lockFilePath);
        }
    }

    /**
     * Write log file entry
     *
     * @param   string  $msg    Text to log
     * @access  public
     */
    public static function log()
    {
        $args = func_get_args();
        if (sizeof($args) > 1) {
            $msg = vsprintf(array_shift($args), $args);
        } else {
            $msg = $args[0];
        }
        self::init();
        $msg = sprintf("%s[%s]: %s\n", date('Y-m-d H:i:s'), getmypid(), $msg);
        if (self::$verbose) {
            echo $msg;
        }
        @error_log($msg, 3, self::$logFilePath);
    }

    /**
     * Public lock file interface
     *
     * @return  bool    TRUE on successful lock, FALSE on error
     * @access  public
     */
    public static function lock()
    {
        self::init();
        clearstatcache();
        if (file_exists(self::$lockFilePath)) {
            self::log('Process is locked');
            return false;
        } elseif (self::realLock()) {
            self::log('Lock obtained');
            return true;
        }
        self::log('Failed to obtain file lock');
        return false;
    }

    /**
     * Public unlock file interface
     *
     * @access  public
     */
    public static function unlock()
    {
        self::init();
        self::realUnlock();
    }

    /**
     * What process holds the lock?
     *
     * @access public
     */
    public static function whoLocked()
    {
        self::init();
        return file_exists(self::$lockFilePath) ? file_get_contents(self::$lockFilePath) : null;
    }

    /**
     * Initialise system
     *
     * @access  public
     */
    public static function init()
    {
        if (!(self::$processHandler instanceof ProcessManager)) {
            self::$processHandler = new ProcessManager();
        }
    }

    /**
     * Destructor
     *
     * Automatically unlocks the process at shutdown
     *
     * @access  public
     */
    public function __destruct()
    {
        self::realUnlock();
    }
}
