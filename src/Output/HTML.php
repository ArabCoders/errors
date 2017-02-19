<?php
/**
 * This file is part of ( \arabcoders\errors ) project.
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

/**
 * Class HTML
 *
 * @package arabcoders\errors\Output
 */
class HTML implements OutputInterface
{
    /**
     * @var MapInterface Map class.
     */
    private $map;

    /**
     * Process data for output.
     *
     * @return OutputInterface
     */
    public function display() : OutputInterface
    {
        if ( !headers_sent() )
        {
            header( $_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500 );
            header( 'Content-Type: text/html; charset=utf-8' );
        }

        $string = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $string .= '<title>Error Page</title>';
        $string .= '<meta name="viewport" content="width=device-width, initial-scale=1.0"></head><body>';
        $string .= '<h1>An %s has Occurred</h1><blockquote>%s</blockquote>%s%s';
        $string .= '</body></html>';

        if ( $this->getMap()->getTrace() )
        {
            $trace = $this->escapeArray( $this->getMap()->getTrace() );
            $trace = sprintf(
                '<h2>Trace Data</h2><blockqoute><pre><code>%s</code></pre></blockqoute>',
                json_encode( $trace, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
            );
        }
        else
        {
            $trace = '';
        }

        if ( $this->getMap()->getStructured() )
        {
            $structured = $this->escapeArray( $this->getMap()->getStructured() );
            $structured = sprintf(
                '<h2>Structured Data</h2><blockqoute><pre><code>%s</code></pre></blockqoute>',
                json_encode( $structured, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
            );
        }
        else
        {
            $structured = '';
        }

        $type = ( ErrorInterface::TYPE_ERROR === $this->getMap()->getType() ) ? 'Error' : 'Exception';

        print sprintf( $string, $type, $this->escapeString( $this->getMap()->getMessage() ), $structured, $trace );

        return $this;
    }

    /**
     * Set map.
     *
     * @param MapInterface $map Map Class.
     *
     * @return OutputInterface
     */
    public function setMap( MapInterface $map ) : OutputInterface
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface
    {
        return $this->map;
    }

    /**
     * Escape Array Key/values.
     *
     * @param array $args The array.
     *
     * @return array
     */
    private function escapeArray( array $args ) : array
    {
        array_walk_recursive( $args, function ( &$key, &$leaf )
        {
            if ( is_string( $leaf ) )
            {
                $leaf = $this->escapeString( $leaf );
                $key  = $this->escapeString( $key );
            }
        } );

        return $args;
    }

    /**
     * Escape Text for HTML output.
     *
     * @param string $text Text to be escaped.
     *
     * @return string
     */
    private function escapeString( string $text ) : string
    {
        return htmlspecialchars( $text );
    }
}