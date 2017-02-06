<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Logging\Interfaces;

use arabcoders\errors\Interfaces\MapInterface;

Interface LoggingInterface
{
    /**
     * Process Log Data.
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
     * Set Map.
     *
     * @param MapInterface $map
     *
     * @return LoggingInterface
     */
    public function setMap( MapInterface $map ) : LoggingInterface;

    /**
     * Get Map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;
}