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
    /**
     * @var resource active handler to a file.
     */
    private $fp;

    /**
     * @var MapInterface Map class.
     */
    private $map;

    /**
     * File constructor.
     *
     * @param string $file filename including path to open for writing
     * @param string $mode mode to open file in defaults to ( <u><b>ab</b></u> ).
     */
    public function __construct( $file, string $mode = 'ab' )
    {
        if ( !( $this->fp = @fopen( $file, $mode ) ) )
        {
            throw new \InvalidArgumentException( sprintf( 'Unable to open (%s) for writing', $file ) );
        }
    }

    /**
     * Process data to log.
     */
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
            $message .= sprintf( 'FILE: %s' . PHP_EOL .
                                 'LINE: %s' . PHP_EOL .
                                 'CALL: %s' . PHP_EOL .
                                 '----------------------------' . PHP_EOL,
                                 $row['file'], $row['line'], $row['call']
            );
        }

        if ( $trace )
        {
            $message .= 'End of Trace' . PHP_EOL . '----------------------------';
        }

        fwrite( $this->fp, trim( $message ) . PHP_EOL );

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
     * Free resources and close file handlers.
     */
    public function __destruct()
    {
        if ( is_resource( $this->fp ) )
        {
            fclose( $this->fp );
        }

        $this->map = null;
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