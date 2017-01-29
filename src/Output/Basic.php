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

use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Output\Interfaces\OutputInterface;

class Basic implements OutputInterface
{
    /**
     * @var MapInterface
     */
    private $map;

    public function display()
    {
        header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500 );
        exit( 1 );
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