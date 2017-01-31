<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Output;

use arabcoders\errors\
{
    Interfaces\MapInterface, Output\Interfaces\OutputInterface
};

class Basic implements OutputInterface
{
    /**
     * @var MapInterface
     */
    private $map;

    const MSG = '500 Internal Server Error';

    public function display()
    {
        header( $_SERVER['SERVER_PROTOCOL'] . ' ' . self::MSG, true, 500 );

        print self::MSG;
    }

    public function setMap( MapInterface $map ) : OutputInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}