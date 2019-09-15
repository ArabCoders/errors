<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Output\Interfaces;

use arabcoders\errors\Interfaces\MapInterface;

/**
 * Interface OutputInterface
 *
 * @package arabcoders\errors\Output\Interfaces
 */
Interface OutputInterface
{
    /**
     * Process data for output.
     *
     * @return OutputInterface
     */
    public function display() : OutputInterface;

    /**
     * Set map.
     *
     * @param MapInterface $map Map class.
     *
     * @return OutputInterface
     */
    public function setMap( MapInterface $map ) : OutputInterface;

    /**
     * Get map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;
}