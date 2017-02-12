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
use arabcoders\errors\Interfaces\StructuredInterface;

/**
 * Class Structured
 *
 * @package arabcoders\errors
 */
class Structured implements StructuredInterface
{
    /**
     * @var string Error message
     */
    protected $message = '';

    /**
     * @var array Structured data.
     */
    protected $structured = [];

    /**
     * Set Message.
     *
     * @param string $message Error message.
     *
     * @return StructuredInterface
     */
    public function setMessage( string $message ) : StructuredInterface
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set error.
     *
     * @param ErrorMapInterface $error Error instance.
     *
     * @return StructuredInterface
     */
    public function setError( ErrorMapInterface $error ) : StructuredInterface
    {
        $this->structured['error'] = [
            'errorType' => ErrorInterface::ERROR_CODES[$error->getNumber()] ?? $error->getNumber(),
            'errorCode' => $error->getNumber(),
            'file'      => $error->getFile(),
            'line'      => $error->getLine(),
            'message'   => $error->getMessage(),
        ];

        return $this;
    }

    /**
     * Set exception
     *
     * @param \Throwable $exception The thrown exception.
     *
     * @return StructuredInterface
     */
    public function setException( \Throwable $exception ) : StructuredInterface
    {
        $this->structured['exception'] = [
            'type'    => get_class( $exception ),
            'code'    => $exception->getCode(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'message' => $exception->getMessage(),
        ];

        return $this;
    }

    /**
     * Process structured data.
     *
     * @return StructuredInterface
     */
    public function process() : StructuredInterface
    {
        $this->structured += [
            'request' => [
                'domain' => strtolower( $_SERVER['HTTP_HOST'] ?? 'localhost' ),
                'method' => $_SERVER['REQUEST_METHOD'] ?? null,
                'uri'    => $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? null,
                'refer'  => $_SERVER['HTTP_REFERER'] ?? null,
            ],
            'trigger' => [
                'ip'    => $_SERVER['REMOTE_ADDR'] ?? null,
                'agent' => $_SERVER['HTTP_USER_AGENT']  ?? null,
            ],
        ];

        return $this;
    }

    /**
     * Get processed structured data.
     *
     * @return array
     */
    public function getStructured() : array
    {
        return $this->structured;
    }

    /**
     * Clear data.
     *
     * @return StructuredInterface
     */
    public function clear() : StructuredInterface
    {
        $this->message    = '';
        $this->structured = [];

        return $this;
    }
}