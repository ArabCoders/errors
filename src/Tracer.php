<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors;

use arabcoders\errors\Interfaces\TracerInterface;

class Tracer implements TracerInterface
{
    private $trace = [];

    /**
     * Context for Tracing.
     *
     * @var array
     */
    private $context = [];

    /**
     * @var array
     */
    private $ignore = [];

    public function setIgnore( array $files ) : TracerInterface
    {
        $this->ignore = array_merge_recursive( [ __FILE__ ], $files );

        return $this;
    }

    public function setContext( array $context ) : TracerInterface
    {
        $this->context = $context;

        return $this;
    }

    public function process() : TracerInterface
    {
        try
        {
            if ( !empty( $this->context ) )
            {
                $tracer = $this->context;
            }
            else
            {
                throw new \RuntimeException( 'to get context' );
            }
        }
        catch ( \Throwable $e )
        {
            $tracer = $e->getTrace();
        }

        $path = realpath( __DIR__ . '/../../' );

        foreach ( $tracer as $number => $trace )
        {
            if ( empty( $trace['file'] ) )
            {
                continue;
            }

            // Strip the current directory from path..
            $trace['file'] = str_replace( [ $path, '\\' ], [ '', '/' ], $trace['file'] );
            $trace['file'] = substr( $trace['file'], 1 );

            foreach ( $this->ignore as $file )
            {
                if ( stripos( $file, $trace['file'] ) !== false )
                {
                    continue 2;
                }
            }

            $args = [];

            /**
             * If include* or require* is not called, do not show arguments - they may contain sensitive information.
             */
            if ( !in_array( $trace['function'], [ 'include', 'require_once', 'require', 'include_once' ] ) )
            {
                $trace['args'] = '';
            }
            else
            {
                if ( !empty( $trace['args'][0] ) )
                {
                    $argument = htmlspecialchars( $trace['args'][0] );
                    $argument = str_replace( [ $path, '\\' ], [ '', '/' ], $argument );
                    $argument = substr( $argument, 1 );
                    $args[]   = "'{$argument}'";
                }
            }

            $trace['class'] = ( empty( $trace['class'] ) ) ? '' : $trace['class'];
            $trace['type']  = ( empty( $trace['type'] ) ) ? '' : $trace['type'];

            $this->trace[] = [
                'file' => $trace['file'],
                'line' => $trace['line'],
                'call' => $trace['class'] . $trace['type'] . $trace['function'] . '(' . ( ( sizeof( $args ) ) ? implode( ', ', $args ) : '' ) . ');',
            ];
        }

        return $this;
    }

    public function getTrace() : array
    {
        return $this->trace;
    }

    public function clear() : TracerInterface
    {
        $this->context = [];
        $this->trace   = [];
        $this->ignore  = [];

        return $this;
    }
}