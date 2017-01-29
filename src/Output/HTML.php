<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Output;

use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Output\Interfaces\OutputInterface;

class HTML implements OutputInterface
{
    /**
     * @var MapInterface
     */
    private $map;

    public function display()
    {
        if ( !headers_sent() )
        {
            header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500 );
            Header( 'Content-Type: text/html; charset=utf-8' );
        }

        $string = '<!DOCTYPE html><html dir="auto"><head><meta charset="utf-8"><title>Error Page</title><meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body><h1>An %s has Occurred</h1><blockquote>%s</blockquote>%s%s</body></html>';

        $trace      = '';
        $structured = '';

        if ( $this->getMap()->getTrace() )
        {
            $trace = $this->escape( $this->getMap()->getTrace() );
            $trace = sprintf( '<h2>Trace Data</h2><blockqoute><pre><code>%s</code></pre></blockqoute>',
                              json_encode( $trace, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE )
            );
        }

        if ( $this->getMap()->getStructured() )
        {
            $structured = $this->escape( $this->getMap()->getStructured() );
            $structured = sprintf( '<h2>Structured Data</h2><blockqoute><pre><code>%s</code></pre></blockqoute>',
                                   json_encode( $structured, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE ) );
        }

        $type = ( $this->getMap()->getType() == ErrorInterface::TYPE_ERROR ) ? 'Error' : 'Exception';
        print sprintf( $string, $type, htmlspecialchars( $this->getMap()->getMessage() ), $structured, $trace );

        exit( 1 );
    }

    public function setMap( MapInterface $map ) : OutputInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }

    private function escape( array $args ) : array
    {
        /** @noinspection PhpUnusedParameterInspection */
        array_walk_recursive( $args, function ( $key, &$leaf )
        {
            if ( is_string( $leaf ) )
            {
                $leaf = htmlspecialchars( $leaf );
            }
        } );

        return $args;
    }
}