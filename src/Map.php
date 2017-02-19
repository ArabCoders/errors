<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors;

use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\ErrorMapInterface;
use arabcoders\errors\Interfaces\MapInterface;

/**
 * Class Map
 *
 * @package arabcoders\errors
 */
class Map implements MapInterface
{
    /**
     * @var array Trace data.
     */
    protected $trace = [];

    /**
     * @var array Structured data
     */
    protected $structured = [];

    /**
     * @var string Error message.
     */
    protected $message = '';

    /**
     * @var int Error type {@see ErrorInterface::TYPE_ERROR} or {@see ErrorInterface::TYPE_EXCEPTION}
     */
    protected $type = 0;

    /**
     * @var ErrorMapInterface Error instance.
     */
    protected $errorMap;

    /**
     * @var \Throwable The thrown exception.
     */
    protected $exceptionMap;

    /**
     * Set error type.
     *
     * @param int $type {@see ErrorInterface::TYPE_ERROR} or {@see ErrorInterface::TYPE_EXCEPTION}
     *
     * @return MapInterface
     */
    public function setType( int $type ) : MapInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get error type.
     *
     * @return int
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Is the type of this instance is {@see ErrorInterface::TYPE_ERROR}.
     *
     * @return bool
     */
    public function isError() : bool
    {
        return ErrorInterface::TYPE_ERROR === $this->getType();
    }

    /**
     * Is the type of this instance is {@see ErrorInterface::TYPE_EXCEPTION}.
     *
     * @return bool
     */
    public function isException() : bool
    {
        return ErrorInterface::TYPE_EXCEPTION === $this->getType();
    }

    /**
     * Set trace data.
     *
     * @param array $trace Formatted trace data.
     *
     * @return MapInterface
     */
    public function setTrace( array $trace ) : MapInterface
    {
        $this->trace = $trace;

        return $this;
    }

    /**
     * Get trace data.
     *
     * @return array
     */
    public function getTrace() : array
    {
        return $this->trace;
    }

    /**
     * Set message.
     *
     * @param string $message Error message.
     *
     * @return MapInterface
     */
    public function setMessage( string $message ) : MapInterface
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage() : string
    {
        return $this->message;
    }

    /**
     * Set structured data.
     *
     * @param array $structured Structured data.
     *
     * @return MapInterface
     */
    public function setStructured( array $structured ) : MapInterface
    {
        $this->structured = $structured;

        return $this;
    }

    /**
     * Get structured data.
     *
     * @return array
     */
    public function getStructured() : array
    {
        return $this->structured;
    }

    /**
     * Set error instance.
     *
     * @param ErrorMapInterface $error Error instance.
     *
     * @return MapInterface
     */
    public function setError( ErrorMapInterface $error ) : MapInterface
    {
        $this->errorMap = $error;

        return $this;
    }

    /**
     * Get error instance.
     *
     * @return ErrorMapInterface
     */
    public function getError() : ErrorMapInterface
    {
        if ( !$this->hasError() )
        {
            throw new \RuntimeException( 'Type is not set as an error.' );
        }

        return $this->errorMap;
    }

    /**
     * Does this map contain {@see ErrorMapInterface} object.
     *
     * @return bool
     */
    public function hasError() : bool
    {
        return ( $this->errorMap instanceof ErrorMapInterface );
    }

    /**
     * Set exception instance.
     *
     * @param \Throwable $exception The thrown exception.
     *
     * @return MapInterface
     */
    public function setException( \Throwable $exception ) : MapInterface
    {
        $this->exceptionMap = $exception;

        return $this;
    }

    /**
     * Get exception instance.
     *
     * @return \Throwable
     */
    public function getException() : \Throwable
    {
        if ( !$this->hasException() )
        {
            throw new \RuntimeException( 'Type is not set as an exception.' );
        }

        return $this->exceptionMap;
    }

    /**
     * Does this map contain {@see \Throwable} object.
     *
     * @return bool
     */
    public function hasException() : bool
    {
        return ( $this->exceptionMap instanceof \Throwable );
    }

    /**
     * Return instance.
     *
     * @return MapInterface
     */
    public function getInstance() : MapInterface
    {
        return $this;
    }

    /**
     * Clear data.
     *
     * @return MapInterface
     */
    public function clear() : MapInterface
    {
        $this->trace        = [];
        $this->structured   = [];
        $this->message      = '';
        $this->type         = 0;
        $this->errorMap     = null;
        $this->exceptionMap = null;

        return $this;
    }
}