<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Logging;

use arabcoders\errors\ErrorMap;
use arabcoders\errors\Formatter;
use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Logging\Interfaces\LoggingInterface;
use arabcoders\errors\Map;
use arabcoders\errors\Structured;
use arabcoders\errors\Tracer;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * To Make Our Loggers PS3 Aware.
 *
 * @package arabcoders\errors\Logging
 */
abstract class PSR3 implements LoggerInterface, LoggingInterface
{
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::EMERGENCY, $message, $context );
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::ALERT, $message, $context );
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::CRITICAL, $message, $context );
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::ERROR, $message, $context );
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::WARNING, $message, $context );
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::NOTICE, $message, $context );
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::INFO, $message, $context );
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug( $message, array $context = [] ) : void
    {
        $this->log( LogLevel::DEBUG, $message, $context );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log( $level, $message, array $context = [] ) : void
    {
        $errorMap = new ErrorMap( $level, $message, '', '' );

        $map = ( new Map() )
            ->clear()
            ->setType( ErrorInterface::TYPE_ERROR )
            ->setStructured( ( new Structured() )->setError( $errorMap )->process()->getStructured() )
            ->setTrace( ( new Tracer() )->setIgnore( [ __FILE__ ] )->setContext( $context )->process()->getTrace() )
            ->setMessage( ( new Formatter() )->formatError( $errorMap ) )
            ->setError( $errorMap )
            ->setId( $this->createUUIDv4() )
            ->getInstance();

        $this->setMap( $map )->process()->clear();
    }

    private function createUUIDv4() : string
    {
        $version = 4;

        $hash = bin2hex( random_bytes( 16 ) );

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            // 32 bits for "time_low"
            substr( $hash, 0, 8 ),
            // 16 bits for "time_mid"
            substr( $hash, 8, 4 ),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number
            ( hexdec( substr( $hash, 12, 4 ) ) & 0x0fff ) | $version << 12,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            ( hexdec( substr( $hash, 16, 4 ) ) & 0x3fff ) | 0x8000,
            // 48 bits for "node"
            substr( $hash, 20, 12 )
        );
    }
}
