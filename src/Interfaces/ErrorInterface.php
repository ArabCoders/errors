<?php
/**
 * This file is part of ( framework ) project.
 *
 * (c) 2017 ArabCoders Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace arabcoders\errors\Interfaces;

use arabcoders\errors\Logging\Interfaces\LoggingInterface;
use arabcoders\errors\Output\Interfaces\OutputInterface;

interface ErrorInterface
{
    CONST TYPE_ERROR     = 0;
    const TYPE_EXCEPTION = 1;

    CONST ERROR_CODES = [
        E_ERROR             => 'E_ERROR',
        E_CORE_ERROR        => 'E_CORE_ERROR',
        E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
        E_PARSE             => 'E_PARSE',
        E_CORE_WARNING      => 'E_CORE_WARNING',
        E_CORE_WARNING      => 'E_COMPILE_WARNING',
        E_USER_ERROR        => 'E_USER_ERROR',
        E_USER_WARNING      => 'E_USER_WARNING',
        E_USER_NOTICE       => 'E_USER_NOTICE',
        E_STRICT            => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED        => 'E_DEPRECATED',
        E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
        E_NOTICE            => 'E_NOTICE',
        E_WARNING           => 'E_WARNING',
    ];

    /**
     * Register Handlers for Error/shutdown/Exceptions.
     *
     * @return ErrorInterface
     */
    public function register() : ErrorInterface;

    /**
     * Set Error/Exception Message Formatter.
     *
     * @param FormatterInterface $formatter
     *
     * @return ErrorInterface
     */
    public function setFormatter( FormatterInterface $formatter ) : ErrorInterface;

    /**
     * Get Formatter.
     *
     * @return FormatterInterface
     */
    public function getFormatter() : FormatterInterface;

    /**
     * Set Output.
     *
     * @param OutputInterface $output
     *
     * @return mixed
     */
    public function setOutput( OutputInterface $output ) : ErrorInterface;

    /**
     * Get Output.
     *
     * @return OutputInterface
     */
    public function getOutput() : OutputInterface;

    /**
     * Set Structured Data Mapper.
     *
     * @param StructuredInterface $structured
     *
     * @return ErrorInterface
     */
    public function setStructured( StructuredInterface $structured ) : ErrorInterface;

    /**
     * Get Structured Mapper.
     *
     * @return StructuredInterface
     */
    public function getStructured() : StructuredInterface;

    /**
     * Set Tracer.
     *
     * @param TracerInterface $tracer
     *
     * @return ErrorInterface
     */
    public function setTracer( TracerInterface $tracer ) : ErrorInterface;

    /**
     * Get Tracer.
     *
     * @return TracerInterface
     */
    public function getTracer() : TracerInterface;

    /**
     * Process Exceptions.
     *
     * @param \Throwable $exception
     *
     * @return ErrorInterface
     */
    public function handleException( \Throwable $exception ) : ErrorInterface;

    /**
     * Process Fetal And normal Errors.
     *
     * @param int    $number
     * @param string $text
     * @param string $file
     * @param int    $line
     *
     * @return ErrorInterface
     */
    public function handleError( int $number, string $text, string $file, int $line ) : ErrorInterface;

    /**
     * Register Specific Handler for special kind of error to be processed separately.
     *
     * @param int                  $number
     * @param SpecialCaseInterface $handler
     *
     * @return ErrorInterface
     */
    public function specialCaseError( int $number, SpecialCaseInterface $handler ) : ErrorInterface;

    /**
     * Register Specific Handler for special kind of Exception to be processed separately.
     *
     * @param string               $name
     * @param SpecialCaseInterface $handler
     *
     * @return ErrorInterface
     */
    public function specialCaseException( string $name, SpecialCaseInterface $handler ) : ErrorInterface;

    /**
     * Register Logger.
     *
     * @param string           $name
     * @param LoggingInterface $logger
     *
     * @return ErrorInterface
     */
    public function registerLogger( string $name, LoggingInterface $logger ) : ErrorInterface;

    /**
     * Remove Logger.
     *
     * @param string $name
     *
     * @return ErrorInterface
     */
    public function removeLogger( string $name ) : ErrorInterface;

    /**
     * Set Map.
     *
     * @param MapInterface $map
     *
     * @return ErrorInterface
     */
    public function setMap( MapInterface $map ) : ErrorInterface;

    /**
     * Get Map.
     *
     * @return MapInterface
     */
    public function getMap() : MapInterface;
}