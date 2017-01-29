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
     * @var MapInterface
     */
    private $map;

    public function __construct( $appName = 'PHPErrors' )
    {
        openlog( $appName, \LOG_PID, \LOG_USER );
    }

    public function process() : LoggingInterface
    {
        $message = $this->getMap()->getMessage();

        if ( $this->getMap()->getStructured() )
        {
            $message .= PHP_EOL . print_r( $this->getMap()->getStructured(), true );
        }

        if ( $this->getMap()->getTrace() )
        {
            $message .= PHP_EOL . print_r( $this->getMap()->getTrace(), true );
        }

        syslog( \LOG_ERR, $message );

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