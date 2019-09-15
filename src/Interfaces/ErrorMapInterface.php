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
 * Interface ErrorMapInterface
 *
 * @package arabcoders\errors\Interfaces
 */
interface ErrorMapInterface
{
    /**
     * ErrorMapInterface constructor.
     *
     * @param int    $number  Error code number.
     * @param string $message Error message.
     * @param string $file    File name where the error was triggered.
     * @param int    $line    Line number where the error was triggered.
     */
    public function __construct( int $number, string $message, string $file, int $line );

    /**
     * Get error code number.
     *
     * @return int
     */
    public function getNumber() : int;

    /**
     * Get error message.
     *
     * @return string
     */
    public function getMessage() : string;

    /**
     * Get the filename where the error was triggered.
     *
     * @return string
     */
    public function getFile() : string;

    /**
     * Get the line number where the error was triggered.
     *
     * @return int
     */
    public function getLine() : int;
}
