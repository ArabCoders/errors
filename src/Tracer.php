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

use arabcoders\errors\Interfaces\TracerInterface;

/**
 * Class Tracer
 *
 * @package arabcoders\errors
 */
class Tracer implements TracerInterface
{
    /**
     * @var array Processed trace data.
     */
    protected $trace = [];

    /**
     * @var array Trace context.
     */
    protected $context = [];

    /**
     * @var array Files to ignore trace data from.
     */
    protected $ignore = [];

    /**
     * @var string Root path to strip from file name.
     */
    protected $root = '';

    /**
     * Set root path to strip from file name.
     *
     * @param string $root
     *
     * @return TracerInterface
     */
    public function setRoot( string $root ) : TracerInterface
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get Root path.
     *
     * @return string
     */
    public function getRoot() : string
    {
        return $this->root;
    }

    /**
     * Ignore trace data from those files.
     *
     * @param array $files
     *
     * @return TracerInterface
     */
    public function setIgnore( array $files ) : TracerInterface
    {
        $this->ignore = array_merge_recursive( [ __FILE__ ], $files );

        return $this;
    }

    /**
     * Set trace context.
     *
     * @param array $context
     *
     * @return TracerInterface
     */
    public function setContext( array $context ) : TracerInterface
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Process trace context.
     *
     * @return TracerInterface
     */
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

        $path = ( $this->getRoot() ) ? realpath( $this->getRoot() ) : '';

        foreach ( $tracer as $number => $trace )
        {
            if ( empty( $trace['file'] ) )
            {
                continue;
            }

            foreach ( $this->ignore as $file )
            {
                if ( false !== stripos( $file, $trace['file'] ) )
                {
                    continue 2;
                }
            }

            // Strip the Root Path from Trace file.
            if ( !empty( $path ) )
            {
                $trace['file'] = str_replace( [ $path, '\\' ], [ '', '/' ], $trace['file'] );
                $trace['file'] = substr( $trace['file'], 1 );
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
                    // Strip the Root Path from Trace file.
                    if ( !empty( $path ) )
                    {
                        $argument = str_replace( [ $path, '\\' ], [ '', '/' ], $argument );
                        $argument = substr( $argument, 1 );
                    }
                    $args[] = "'{$argument}'";
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

    /**
     * Get processed trace data.
     *
     * @return array
     */
    public function getTrace() : array
    {
        return $this->trace;
    }

    /**
     * Clear data.
     *
     * @return TracerInterface
     */
    public function clear() : TracerInterface
    {
        $this->context = [];
        $this->trace   = [];
        $this->ignore  = [];
        $this->root    = '';

        return $this;
    }
}