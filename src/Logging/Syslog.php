<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Logging;

use arabcoders\errors\
{
    Interfaces\ErrorInterface, Interfaces\MapInterface, Logging\Interfaces\LoggingInterface
};

/**
 * Class Syslog
 *
 * @package arabcoders\errors\Logging
 */
class Syslog implements LoggingInterface
{
    /**
     * @var MapInterface
     */
    private $map;

    /**
     * @var bool
     */
    private $syslog;

    /**
     * @var array Map PHP Errors to syslog.
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

    public function __construct( $appName = 'PHPErrors' )
    {
        if ( function_exists( 'openlog' ) )
        {
            openlog( $appName, \LOG_PID, \LOG_USER );
        }

        $this->syslog = function_exists( 'syslog' );
    }

    public function process() : LoggingInterface
    {
        if ( !function_exists( 'syslog' ) )
        {
            throw new \RuntimeException( 'syslog(): function does not exists' );
        }

        $message = $this->getMap()->getMessage();

        if ( $this->getMap()->getStructured() )
        {
            $message .= PHP_EOL . print_r( $this->getMap()->getStructured(), true );
        }

        if ( $this->getMap()->getTrace() )
        {
            $message .= PHP_EOL . print_r( $this->getMap()->getTrace(), true );
        }

        if ( $this->getMap()->getType() == ErrorInterface::TYPE_EXCEPTION )
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

    public function clear() : LoggingInterface
    {
        $this->map = null;

        return $this;
    }

    public function setMap( MapInterface $map ) : LoggingInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}