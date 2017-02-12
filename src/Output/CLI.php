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
 * Class CLI
 *
 * @package arabcoders\errors\Output
 */
class CLI implements OutputInterface
{
    /**
     * @var MapInterface Map class.
     */
    private $map;

    /**
     * Process data for output.
     *
     * @return OutputInterface
     */
    public function display() : OutputInterface
    {
        if ( !is_resource( STDERR ) )
        {
            define( 'STDERR', fopen( 'php://stderr', 'w' ) );
        }

        fwrite( STDERR, $this->getMap()->getMessage() . PHP_EOL );

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