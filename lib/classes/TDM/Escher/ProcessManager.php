<?php

/**
 * Escher Framework v2.0
 *
 * @copyright 2000-2015 Digital Design Labs Ltd
 * @package \TDM\Escher
 * @license https://raw.github.com/digitaldesignlabs/escher/master/LICENSE
 */

namespace TDM\Escher;

/**
 * ProcessManager
 *
 * A Process Manager for PHP batch scripts, with support for locking and logging
 *
 * @author Mike Hall
 * @copyright 2014 Digital Design Labs Ltd
 * @todo Needs a complete overall, makes some unwarranted assumptions about writable paths
 */

class ProcessManager
{
    /**
     * Contains the path to the lock file
     *
     * @var string
     * @access private
     */
    private static $lockFilePath = null;

    /**
     * Contains the path to the log file
     *
     * @var string
     * @access private
     */
    private static $logFilePath = null;

    /**
     * Contains an instance of this object
     *
     * @var object
     * @access private
     */
    private static $processHandler = null;

    /**
     * Should I echo log messages?
     *
     * @var bool
     * @access public
     */
    public static $verbose = NO;

    /**
     * Constructor
     *
     * @return void
     * @access private
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
     * @return bool - YES on successful lock, NO on error
     * @access private
     */
    private static function realLock()
    {
        clearstatcache();
        if (!file_exists(self::$lockFilePath)) {
            return file_put_contents(self::$lockFilePath, getmypid());
        }
        return NO;
    }

    /**
     * Unlock current script
     *
     * Removes the current lock file, if it was created by this process
     *
     * @access private
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
     * @param string $msg - Text to log
     * @access public
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
     * @return bool - YES on successful lock, NO on error
     * @access public
     */
    public static function lock()
    {
        self::init();
        clearstatcache();
        if (file_exists(self::$lockFilePath)) {
            self::log('Process is locked');
            return NO;
        } elseif (self::realLock()) {
            self::log('Lock obtained');
            return YES;
        }
        self::log('Failed to obtain file lock');
        return NO;
    }

    /**
     * Public unlock file interface
     *
     * @access public
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
     * @access public
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
     * @access public
     */
    public function __destruct()
    {
        self::realUnlock();
    }
}
