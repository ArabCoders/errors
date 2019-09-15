<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Logging\Interfaces;

use arabcoders\errors\Interfaces\MapInterface;

/**
 * Interface LoggingInterface
 *
 * @package arabcoders\errors\Logging\Interfaces
 */
Interface LoggingInterface
{
    /**
     * Process data to log.
     *
     * @return LoggingInterface
     */
    public function process() : LoggingInterface;

    /**
     * Clear log data.
     *
     * @return LoggingInterface
     */
    public function clear() : LoggingInterface;

    /**
     * Set map.
     *
     * @param MapInterface $map Map class.
     *
     * @return LoggingInterface
     */
    public function setMap( MapInterface $map ) : LoggingInterface;

    /**
     * Get map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;
}