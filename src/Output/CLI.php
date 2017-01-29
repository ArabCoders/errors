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

class CLI implements OutputInterface
{
    /**
     * @var MapInterface
     */
    private $map;

    public function display()
    {
        fwrite( STDERR, $this->getMap()->getMessage() . PHP_EOL );

        return '';
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