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

/**
 * Interface TracerInterface
 *
 * @package arabcoders\errors\Interfaces
 */
interface TracerInterface
{
    /**
     * Set root path to strip from file name, for example
     * if $root is set to "/home/foo/" and the file full path is "/home/foo/bar/file.php", it will be shown as
     * /bar/file.php.
     *
     * @param string $root Root path
     *
     * @return TracerInterface
     */
    public function setRoot( string $root ) : TracerInterface;

    /**
     * Get root path.
     *
     * @return string
     */
    public function getRoot() : string;

    /**
     * Set trace context.
     *
     * @param array $context Trace context.
     *
     * @return TracerInterface
     */
    public function setContext( array $context ) : TracerInterface;

    /**
     * Ignore trace data from those files.
     *
     * @param array $files List of files to be ignored in the generated trace data.
     *
     * @return TracerInterface
     */
    public function setIgnore( array $files ) : TracerInterface;

    /**
     * Process trace context.
     *
     * @return TracerInterface
     */
    public function process() : TracerInterface;

    /**
     * Get processed trace data.
     *
     * @return array
     */
    public function getTrace() : array;

    /**
     * Clear data.
     *
     * @return TracerInterface
     */
    public function clear() : TracerInterface;
}