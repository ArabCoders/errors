<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Interfaces;

interface MapInterface
{
    /**
     * Does The map Contain an error
     *
     * @return bool
     */
    public function isError() : bool;

    /**
     * Does The map Contain an Exception
     *
     * @return bool
     */
    public function isException() : bool;

    /**
     * Set Error Type.
     *
     * @param int $type
     *
     * @return MapInterface
     */
    public function setType( int $type ) : MapInterface;

    /**
     * Get Error Type.
     *
     * @return int
     */
    public function getType() : int;

    /**
     * set Trace.
     *
     * @param array $trace
     *
     * @return MapInterface
     */
    public function setTrace( array $trace ) : MapInterface;

    /**
     * Get Trace.
     *
     * @return array
     */
    public function getTrace() : array;

    /**
     * Set Message.
     *
     * @param string $message
     *
     * @return MapInterface
     */
    public function setMessage( string $message ) : MapInterface;

    /**
     * Get Message.
     *
     * @return string
     */
    public function getMessage() : string;

    /**
     * Set Structured.
     *
     * @param array $structured
     *
     * @return MapInterface
     */
    public function setStructured( array $structured ) : MapInterface;

    /**
     * Get Structured
     *
     * @return array
     */
    public function getStructured() : array;

    /**
     * Clear Data.
     *
     * @return MapInterface
     */
    public function clear() : MapInterface;

    /**
     * @param ErrorMapInterface $errorMap
     *
     * @return MapInterface
     */
    public function setError( ErrorMapInterface $errorMap ) : MapInterface;

    /**
     * Get Error
     *
     * @return ErrorMapInterface
     */
    public function getError() : ErrorMapInterface;

    /**
     * Set Exception.
     *
     * @param \Throwable $exception
     *
     * @return MapInterface
     */
    public function setException( \Throwable $exception ) : MapInterface;

    /**
     * Get Exception.
     *
     * @return \Throwable
     */
    public function getException() : \Throwable;

    /**
     * Return this object.
     *
     * @return MapInterface
     */
    public function getInstance() : MapInterface;
}