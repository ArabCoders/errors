<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Interfaces;

/**
 * Interface ListenerInterface
 *
 * @package arabcoders\errors\Interfaces
 */
Interface ListenerInterface
{
    /**
     * Handle the exception or error
     */
    public function handle();

    /**
     * Set Map.
     *
     * @param MapInterface $map Map class.
     *
     * @return ListenerInterface
     */
    public function setMap( MapInterface $map ) : ListenerInterface;

    /**
     * Get Map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;
}