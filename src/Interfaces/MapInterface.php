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
 * Interface MapInterface
 *
 * @package arabcoders\errors\Interfaces
 */
interface MapInterface
{
    /**
     * Set error type.
     *
     * @param int $type {@see ErrorInterface::TYPE_ERROR} or {@see ErrorInterface::TYPE_EXCEPTION}
     *
     * @return MapInterface
     */
    public function setType( int $type ) : MapInterface;

    /**
     * Get error type.
     *
     * @return int
     */
    public function getType() : int;

    /**
     * Is the type of this instance is {@see ErrorInterface::TYPE_ERROR}.
     *
     * @return bool
     */
    public function isError() : bool;

    /**
     * Is the type of this instance is {@see ErrorInterface::TYPE_EXCEPTION}.
     *
     * @return bool
     */
    public function isException() : bool;

    /**
     * Set trace data.
     *
     * @param array $trace Formatted trace data.
     *
     * @return MapInterface
     */
    public function setTrace( array $trace ) : MapInterface;

    /**
     * Get trace data.
     *
     * @return array
     */
    public function getTrace() : array;

    /**
     * Set Unique Id for error.
     *
     * @param string $id
     *
     * @return MapInterface
     */
    public function setId( string $id ) : MapInterface;

    /**
     * Get Unique Error Id.
     *
     * @return string
     */
    public function getId() : string;

    /**
     * Set message.
     *
     * @param string $message Error message.
     *
     * @return MapInterface
     */
    public function setMessage( string $message ) : MapInterface;

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage() : string;

    /**
     * Set structured data.
     *
     * @param array $structured Structured data.
     *
     * @return MapInterface
     */
    public function setStructured( array $structured ) : MapInterface;

    /**
     * Get structured data.
     *
     * @return array
     */
    public function getStructured() : array;

    /**
     * Set error instance.
     *
     * @param ErrorMapInterface $error Error instance.
     *
     * @return MapInterface
     */
    public function setError( ErrorMapInterface $error ) : MapInterface;

    /**
     * Get error instance.
     *
     * @return ErrorMapInterface
     */
    public function getError() : ErrorMapInterface;

    /**
     * Does this map contain {@see ErrorMapInterface} object.
     *
     * @return bool
     */
    public function hasError() : bool;

    /**
     * Set exception instance.
     *
     * @param \Throwable $exception The thrown exception.
     *
     * @return MapInterface
     */
    public function setException( \Throwable $exception ) : MapInterface;

    /**
     * Get exception instance.
     *
     * @return \Throwable
     */
    public function getException() : \Throwable;

    /**
     * Does this map contain {@see \Throwable} object.
     *
     * @return bool
     */
    public function hasException() : bool;

    /**
     * Return instance.
     *
     * @return MapInterface
     */
    public function getInstance() : MapInterface;

    /**
     * Clear data.
     *
     * @return MapInterface
     */
    public function clear() : MapInterface;
}