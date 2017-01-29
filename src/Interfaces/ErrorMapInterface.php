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

interface ErrorMapInterface
{
    public function __construct( int $number, string $message, string $file, string $line );

    public function getNumber() : int;

    public function getMessage() : string;

    public function getFile() : string;

    public function getLine() : int;
}
