<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger
{
    /**
     * @var LoggerInterface
     */
    private static $logger;

    /**
     * @var array
     */
    private static $defaultLevels = [
        LogLevel::EMERGENCY => LOG_EMERG,
        LogLevel::ALERT     => LOG_ALERT,
        LogLevel::CRITICAL  => LOG_CRIT,
        LogLevel::ERROR     => LOG_ERR,
        LogLevel::WARNING   => LOG_WARNING,
        LogLevel::NOTICE    => LOG_NOTICE,
        LogLevel::INFO      => LOG_INFO,
        LogLevel::DEBUG     => LOG_INFO,
    ];

    /**
     * @param LoggerInterface $logger
     */
    public static function set( LoggerInterface $logger ) : void
    {
        static::$logger = $logger;
    }

    /**
     * @param string|int $level
     * @param string     $message
     * @param array      $context
     */
    public static function log( $level, string $message, array $context = [] ) : void
    {
        if ( static::$logger === null )
        {
            static::dlogger( $level, $message );

            return;
        }

        static::$logger->log( $level, $message, $context );
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public static function alert( string $message, array $context = [] ) : void
    {
        static::log( LogLevel::ALERT, $message, $context );
    }

    /**
     * @param       $message
     * @param array $context
     */
    public static function critical( $message, $context = [] ) : void
    {
        static::log( LogLevel::CRITICAL, $message, $context );
    }

    /**
     * @param       $message
     * @param array $context
     */
    public static function debug( $message, $context = [] ) : void
    {
        static::log( LogLevel::DEBUG, $message, $context );
    }

    /**
     * @param       $message
     * @param array $context
     */
    public static function emergency( $message, $context = [] ) : void
    {
        static::log( LogLevel::EMERGENCY, $message, $context );
    }

    /**
     * @param       $message
     * @param array $context
     */
    public static function error( $message, $context = [] ) : void
    {
        static::log( LogLevel::ERROR, $message, $context );
    }

    /**
     * @param       $message
     * @param array $context
     */
    public static function info( $message, $context = [] ) : void
    {
        static::log( LogLevel::INFO, $message, $context );
    }

    /**
     * @param       $message
     * @param array $context
     */
    public static function notice( $message, $context = [] ) : void
    {
        static::log( LogLevel::NOTICE, $message, $context );
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public static function warning( $message, $context = [] ) : void
    {
        static::log( LogLevel::WARNING, $message, $context );
    }

    /**
     * Defaults to Syslog.
     *
     * @param        $level
     * @param string $message
     */
    private static function dlogger( $level, string $message ) : void
    {
        $level = !empty( static::$defaultLevels[$level] ) ? static::$defaultLevels[$level] : LOG_ERR;
        syslog( $level, $message );
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    public function __wakeup()
    {
    }
}
