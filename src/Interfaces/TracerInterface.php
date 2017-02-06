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
     * Set Root Path To strip from Trace.
     *
     * @param string $root
     *
     * @return TracerInterface
     */
    public function setRoot( string $root ) : TracerInterface;

    /**
     * Get Root Path.
     *
     * @return string
     */
    public function getRoot() : string;

    /**
     * Set Debug Trace Context.
     *
     * @param array $context
     *
     * @return TracerInterface
     */
    public function setContext( array $context ) : TracerInterface;

    /**
     * Ignore Calls from these files.
     *
     * @param array $files
     *
     * @return TracerInterface
     */
    public function setIgnore( array $files ) : TracerInterface;

    /**
     * Process the Trace.
     *
     * @return TracerInterface
     */
    public function process() : TracerInterface;

    /**
     * Get Trace Data.
     *
     * @return array
     */
    public function getTrace() : array;

    /**
     * Clear Data.
     *
     * @return TracerInterface
     */
    public function clear() : TracerInterface;
}