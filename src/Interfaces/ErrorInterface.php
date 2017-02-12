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

use arabcoders\errors\Logging\Interfaces\LoggingInterface;
use arabcoders\errors\Output\Interfaces\OutputInterface;

/**
 * Interface ErrorInterface
 *
 * @package arabcoders\errors\Interfaces
 */
interface ErrorInterface
{
    /**
     * @var int Type error.
     */
    const TYPE_ERROR = 0;

    /**
     * @var int Type exception.
     */
    const TYPE_EXCEPTION = 1;

    /**
     * @var array All PHP core errors.
     */
    const ERROR_CODES = [
        E_ERROR             => 'E_ERROR',
        E_WARNING           => 'E_WARNING',
        E_PARSE             => 'E_PARSE',
        E_NOTICE            => 'E_NOTICE',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_STRICT            => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED        => 'E_DEPRECATED',
        E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
    ];

    /**
     * @var array Fatal errors
     */
    const FATAL_ERRORS = [
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_CORE_WARNING,
        E_COMPILE_ERROR,
        E_COMPILE_WARNING
    ];

    /**
     * Register handlers for error/shutdown/exceptions.
     *
     * @return ErrorInterface
     */
    public function register() : ErrorInterface;

    /**
     * Set message formatter.
     *
     * @param FormatterInterface $formatter Message formatter instance.
     *
     * @return ErrorInterface
     */
    public function setFormatter( FormatterInterface $formatter ) : ErrorInterface;

    /**
     * Get formatter.
     *
     * @return FormatterInterface
     */
    public function getFormatter() : FormatterInterface;

    /**
     * Set output.
     *
     * @param OutputInterface $output Output instance.
     *
     * @return mixed
     */
    public function setOutput( OutputInterface $output ) : ErrorInterface;

    /**
     * Get output.
     *
     * @return OutputInterface
     */
    public function getOutput() : OutputInterface;

    /**
     * Set structured mapper.
     *
     * @param StructuredInterface $structured Structured instance.
     *
     * @return ErrorInterface
     */
    public function setStructured( StructuredInterface $structured ) : ErrorInterface;

    /**
     * Get structured mapper.
     *
     * @return StructuredInterface
     */
    public function getStructured() : StructuredInterface;

    /**
     * Set Tracer.
     *
     * @param TracerInterface $tracer Tracer instance.
     *
     * @return ErrorInterface
     */
    public function setTracer( TracerInterface $tracer ) : ErrorInterface;

    /**
     * Get tracer.
     *
     * @return TracerInterface
     */
    public function getTracer() : TracerInterface;

    /**
     * Process exception.
     *
     * @param \Throwable $exception The thrown exception
     *
     * @return ErrorInterface
     */
    public function handleException( \Throwable $exception ) : ErrorInterface;

    /**
     * Process error.
     *
     * @param ErrorMapInterface $error The error
     *
     * @return ErrorInterface
     */
    public function handleError( ErrorMapInterface $error ) : ErrorInterface;

    /**
     * Add Listener for specific exception or error.
     *
     * @param string|int        $parameter Class FQN or error number.
     * @param string            $name      Name to refer to this Listener.
     * @param ListenerInterface $handler   The initialized handler.
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if parameter is neither string nor int.
     */
    public function addListener( $parameter, string $name, ListenerInterface $handler ) : ErrorInterface;

    /**
     * Delete listener.
     *
     * @param string|int $parameter Class FQN or error number.
     * @param string     $name      The name that was used in {@see addListener}
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if parameter does not exists or listener name is not registered.
     */
    public function deleteListener( $parameter, string $name ) : ErrorInterface;

    /**
     * Add logging service.
     *
     * @param string           $name   Name to refer to this logging service.
     * @param LoggingInterface $logger The initialized handler.
     *
     * @return ErrorInterface
     */
    public function addLogger( string $name, LoggingInterface $logger ) : ErrorInterface;

    /**
     * Delete logging service.
     *
     * @param string $name The name that was used in {@see addLogger}
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if logger name is not registered.
     */
    public function deleteLogger( string $name ) : ErrorInterface;

    /**
     * Set map.
     *
     * @param MapInterface $map Map class.
     *
     * @return ErrorInterface
     */
    public function setMap( MapInterface $map ) : ErrorInterface;

    /**
     * Get map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;

    /**
     * Add policy.
     *
     * @param string          $name   Name to refer to this policy.
     * @param PolicyInterface $policy The initialized policy.
     *
     * @return ErrorInterface
     */
    public function addPolicy( string $name, PolicyInterface $policy ) : ErrorInterface;

    /**
     * Delete policy.
     *
     * @param string|int $parameter Class FQN or error number.
     * @param string     $name      The name that was used in {@see addPolicy}
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if parameter does not exists or policy name is not registered.
     */
    public function deletePolicy( $parameter, string $name ) : ErrorInterface;
}