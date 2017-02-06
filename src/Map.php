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

    private $trace      = [];
    private $structured = [];
    private $message    = '';
    private $type       = 0;
    private $errorMap;
    private $exceptionMap;

    public function setType( int $type ) : MapInterface
    {
        $this->type = $type;

        return $this;
    }

    public function getType() : int
    {
        return $this->type;
    }

    public function isError() : bool
    {
        return $this->getType() === ErrorInterface::TYPE_ERROR;
    }

    public function isException() : bool
    {
        return $this->getType() === ErrorInterface::TYPE_EXCEPTION;
    }

    public function setTrace( array $trace ) : MapInterface
    {
        $this->trace = $trace;

        return $this;
    }

    public function getTrace() : array
    {
        return $this->trace;
    }

    public function setMessage( string $message ) : MapInterface
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function setStructured( array $structured ) : MapInterface
    {
        $this->structured = $structured;

        return $this;
    }

    public function getStructured() : array
    {
        return $this->structured;
    }

    public function clear() : MapInterface
    {
        $this->trace      = [];
        $this->structured = [];
        $this->message    = '';

        return $this;
    }

    public function getInstance() : MapInterface
    {
        return $this;
    }

    public function setError( ErrorMapInterface $errorMap ) : MapInterface
    {
        $this->errorMap = $errorMap;

        return $this;
    }

    public function getError() : ErrorMapInterface
    {
        if ( !( ( $this->errorMap instanceof ErrorMapInterface ) ) )
        {
            throw new \RuntimeException( 'Type is not set as an error.' );
        }

        return $this->errorMap;
    }

    public function setException( \Throwable $exception ) : MapInterface
    {
        $this->exceptionMap = $exception;

        return $this;
    }

    public function getException() : \Throwable
    {
        if ( !( ( $this->exceptionMap instanceof \Throwable ) ) )
        {
            throw new \RuntimeException( 'Type is not set as an exception.' );
        }

        return $this->exceptionMap;
    }
}