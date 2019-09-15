<?php
/**
 * This file is part of ( @package \arabcoders\errors ) project.
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
    /**
     * @var int Error code number.
     */
    protected $number = 0;

    /**
     * @var string Error message.
     */
    protected $message = '';

    /**
     * @var string Filename where the error was triggered.
     */
    protected $file = '';

    /**
     * @var int Line number where the error was triggered.
     */
    protected $line = 0;

    /**
     * ErrorMapInterface constructor.
     *
     * @param int    $number  Error code number.
     * @param string $message Error message.
     * @param string $file    File name where the error was triggered.
     * @param int    $line    Line number where the error was triggered.
     */
    public function __construct( int $number, string $message, string $file, int $line )
    {
        $this->number  = $number;
        $this->message = $message;
        $this->file    = $file;
        $this->line    = $line;
    }

    /**
     * Get error code number.
     *
     * @return int
     */
    public function getNumber() : int
    {
        return $this->number;
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * Get the filename where the error was triggered.
     *
     * @return string
     */
    public function getFile() : string
    {
        return $this->file;
    }

    /**
     * Get the line number where the error was triggered.
     *
     * @return int
     */
    public function getLine() : int
    {
        return $this->line;
    }
}