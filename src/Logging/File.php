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

use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Logging\Interfaces\LoggingInterface;

/**
 * Class File
 *
 * @package arabcoders\errors\Logging
 */
class File implements LoggingInterface
{
    private $message = '';

    private $trace = [];

    private $structured = [];

    /**
     * @var resource
     */
    private $fp;

    /**
     * @var MapInterface
     */
    private $map;

    /**
     * File constructor.
     *
     * @param string $file
     * @param string $mode
     */
    public function __construct( $file, string $mode = 'ab' )
    {
        if ( !( $this->fp = fopen( $file, $mode ) ) )
        {
            throw new \InvalidArgumentException( sprintf( 'Unable to open (%s) for writing', $file ) );
        }
    }

    public function process() : LoggingInterface
    {
        $message = trim( '[' . gmdate( \DateTime::W3C ) . '] ' . $this->getMap()->getMessage() );
        $trace   = $this->getMap()->getTrace();

        if ( $trace )
        {
            $message .= PHP_EOL .
                '----------------------------' . PHP_EOL .
                'Trace' . PHP_EOL .
                '----------------------------' . PHP_EOL;
        }

        foreach ( $trace as $row )
        {
            $message .= sprintf( 'FILE: %s' . PHP_EOL . 'LINE: %s' . PHP_EOL . 'CALL: %s' . PHP_EOL . '----------------------------' . PHP_EOL,
                                 $row['file'], $row['line'], $row['call']
            );
        }

        if ( $trace )
        {
            $message .= 'End of Trace' . PHP_EOL . '----------------------------';
        }

        fwrite( $this->fp, $message . PHP_EOL );

        return $this;
    }

    public function clear() : LoggingInterface
    {
        $this->message    = '';
        $this->trace      = [];
        $this->structured = [];

        return $this;
    }

    public function __destruct()
    {
        if ( is_resource( $this->fp ) )
        {
            fclose( $this->fp );
        }
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