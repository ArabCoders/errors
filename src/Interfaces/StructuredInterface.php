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
 * Interface StructuredInterface
 *
 * @package arabcoders\errors\Interfaces
 */
Interface StructuredInterface
{
    /**
     * Set Message.
     *
     * @param string $message Error message.
     *
     * @return StructuredInterface
     */
    public function setMessage( string $message ) : StructuredInterface;

    /**
     * Set exception
     *
     * @param \Throwable $exception The thrown exception.
     *
     * @return StructuredInterface
     */
    public function setException( \Throwable $exception ) : StructuredInterface;

    /**
     * Set error.
     *
     * @param ErrorMapInterface $error Error instance.
     *
     * @return StructuredInterface
     */
    public function setError( ErrorMapInterface $error ) : StructuredInterface;

    /**
     * Process structured data.
     *
     * @return StructuredInterface
     */
    public function process() : StructuredInterface;

    /**
     * Get processed structured data.
     *
     * @return array
     */
    public function getStructured() : array;

    /**
     * Clear data.
     *
     * @return StructuredInterface
     */
    public function clear() : StructuredInterface;
}