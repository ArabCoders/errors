<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace arabcoders\errors\Interfaces;

/**
 * Interface SpecialCaseInterface
 *
 * @package arabcoders\errors\Interfaces
 */
Interface SpecialCaseInterface
{
    /**
     * Handle The expection or error provided by the map
     */
    public function handle();

    /**
     * Set Map.
     *
     * @param MapInterface $map
     *
     * @return SpecialCaseInterface
     */
    public function setMap( MapInterface $map ) : SpecialCaseInterface;

    /**
     * Get Map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;
}
