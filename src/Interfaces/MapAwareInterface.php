<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Interfaces;

interface MapAwareInterface
{
    /**
     * Set Map.
     *
     * @param MapInterface $map
     *
     * @return MapAwareInterface
     */
    public function setMap( MapInterface $map ) : MapAwareInterface;

    /**
     * Get Map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;
}