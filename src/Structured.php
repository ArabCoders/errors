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

use arabcoders\errors\Interfaces\ErrorMapInterface;
use arabcoders\errors\Interfaces\StructuredInterface;

class Structured implements StructuredInterface
{
    private $message = '';

    private $trace = [];

    private $structured = [];

    public function setError( ErrorMapInterface $map ) : StructuredInterface
    {
        $this->structured['error'] = [
            'errorType' => Interfaces\ErrorInterface::ERROR_CODES[$map->getNumber()] ?? $map->getNumber(),
            'errorCode' => $map->getNumber(),
            'file'      => $map->getFile(),
            'line'      => $map->getLine(),
            'message'   => $map->getMessage(),
        ];

        return $this;
    }

    public function setException( \Throwable $e ) : StructuredInterface
    {
        $this->structured['exception'] = [
            'type'    => get_class( $e ),
            'code'    => $e->getCode(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'message' => $e->getMessage(),
        ];

        return $this;
    }

    /**
     * Set Message.
     *
     * @param string $message
     *
     * @return StructuredInterface
     */
    public function setMessage( string $message ) : StructuredInterface
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Process Log Data.
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
     * Get Processed Structured Data.
     *
     * @return array
     */
    public function getStructured() : array
    {
        return $this->structured;
    }

    public function clear() : StructuredInterface
    {
        $this->structured = [];
        $this->trace      = [];
        $this->message    = '';

        return $this;
    }
}