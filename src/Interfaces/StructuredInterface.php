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

Interface StructuredInterface
{
    /**
     * Set Message.
     *
     * @param string $message
     *
     * @return StructuredInterface
     */
    public function setMessage( string $message ) : StructuredInterface;

    /**
     * Set Exception
     *
     * @param \Throwable $e
     *
     * @return StructuredInterface
     */
    public function setException( \Throwable $e ) : StructuredInterface;

    /**
     * Set Error.
     *
     * @param ErrorMapInterface $errorMap
     *
     * @return StructuredInterface
     */
    public function setError( ErrorMapInterface $errorMap ) : StructuredInterface;

    /**
     * Process Log Data.
     *
     * @return StructuredInterface
     */
    public function process() : StructuredInterface;

    /**
     * Get Processed Structured Data.
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