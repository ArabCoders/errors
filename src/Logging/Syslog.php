<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Logging;

use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Logging\Interfaces\LoggingInterface;

/**
 * Class Syslog
 *
 * @package arabcoders\errors\Logging
 */
class Syslog implements LoggingInterface
{
    /**
     * @var MapInterface Map class.
     */
    private $map;

    /**
     * @var bool whether syslog function exists.
     */
    private $syslog;

    /**
     * @var array Map PHP errors to syslog.
     */
    private $logMap = [
        E_ERROR             => LOG_ERR,
        E_WARNING           => LOG_WARNING,
        E_PARSE             => LOG_CRIT,
        E_NOTICE            => LOG_NOTICE,
        E_CORE_ERROR        => LOG_ALERT,
        E_CORE_WARNING      => LOG_WARNING,
        E_COMPILE_ERROR     => LOG_ALERT,
        E_COMPILE_WARNING   => LOG_WARNING,
        E_USER_ERROR        => LOG_ERR,
        E_USER_WARNING      => LOG_WARNING,
        E_USER_NOTICE       => LOG_NOTICE,
        E_STRICT            => LOG_DEBUG,
        E_RECOVERABLE_ERROR => LOG_ERR,
        E_DEPRECATED        => LOG_DEBUG,
    ];

    /**
     * Syslog constructor.
     *
     * @param string $appName  App name.
     * @param int    $option   The option argument is used to indicate what logging options will be used when generating a log message.
     * @param int    $facility he facility argument is used to specify what type of program is logging the message. This allows you to
     *                         specify (in your machine's syslog configuration) how messages coming from different facilities will be
     *                         handled.
     */
    public function __construct( $appName = 'PHPErrors', $option = \LOG_PID, $facility = \LOG_USER )
    {
        if ( function_exists( 'openlog' ) )
        {
            openlog( $appName, $option, $facility );
        }

        $this->syslog = function_exists( 'syslog' );
    }

    /**
     * Process data to log.
     */
    public function process() : LoggingInterface
    {
        if ( !$this->syslog )
        {
            throw new \RuntimeException( 'syslog(): function does not exists' );
        }

        $message = $this->getMap()->getMessage();

        if ( $this->getMap()->getId() )
        {
            $message .= ' REF [ ' . $this->getMap()->getId() . ' ]';
        }

        if ( $this->getMap()->getStructured() )
        {
            $message .= PHP_EOL . print_r( $this->getMap()->getStructured(), true );
        }

        if ( $this->getMap()->getTrace() )
        {
            $message .= PHP_EOL . print_r( $this->getMap()->getTrace(), true );
        }

        if ( ErrorInterface::TYPE_EXCEPTION === $this->getMap()->getType() )
        {
            $logType = \LOG_ERR;
        }
        else
        {
            $logType = $this->logMap[$this->getMap()->getError()->getNumber()] ?? \LOG_ERR;
        }

        syslog( $logType, $message );

        return $this;
    }

    /**
     * Clear log data.
     *
     * @return LoggingInterface
     */
    public function clear() : LoggingInterface
    {
        $this->map = null;

        return $this;
    }

    /**
     * Set map.
     *
     * @param MapInterface $map Map class.
     *
     * @return LoggingInterface
     */
    public function setMap( MapInterface $map ) : LoggingInterface
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface
    {
        return $this->map;
    }
}