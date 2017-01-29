<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors;

use arabcoders\errors\Interfaces\ErrorMapInterface;

/**
 * Class Map
 *
 * @package arabcoders\errors
 */
class ErrorMap implements ErrorMapInterface
{
    private $number  = 0;
    private $message = '';
    private $file    = '';
    private $line    = 0;

    public function __construct( int $number, string $message, string $file, string $line )
    {
        $this->number  = $number;
        $this->message = $message;
        $this->file    = $file;
        $this->line    = $line;
    }

    public function getNumber() : int
    {
        return $this->number;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getFile() : string
    {
        return $this->file;
    }

    public function getLine() : int
    {
        return $this->line;
    }
}