<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Output;

use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Output\Interfaces\OutputInterface;

/**
 * Class Basic
 *
 * @package arabcoders\errors\Output
 */
class Basic implements OutputInterface
{
    /**
     * @var MapInterface Map class.
     */
    private $map;

    /**
     * @var string Basic message to display.
     */
    const MSG = '500 Internal Server Error';

    /**
     * Set Header and display Message.
     *
     * @return OutputInterface
     */
    public function display() : OutputInterface
    {
        if ( !headers_sent() )
        {
            header( $_SERVER['SERVER_PROTOCOL'] . ' ' . self::MSG, true, 500 );
        }

        print self::MSG;

        if ( $this->getMap()->getId() )
        {
            echo PHP_EOL . sprintf( 'Unique Error Id: %s', $this->getMap()->getId() );
        }

        return $this;
    }

    /**
     * Set map.
     *
     * @param MapInterface $map Map Class.
     *
     * @return OutputInterface
     */
    public function setMap( MapInterface $map ) : OutputInterface
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