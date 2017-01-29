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

use arabcoders\errors\Interfaces\FormatterInterface,
    arabcoders\errors\Interfaces\SpecialCaseInterface,
    arabcoders\errors\Interfaces\ErrorInterface,
    arabcoders\errors\Interfaces\StructuredInterface,
    arabcoders\errors\Interfaces\TracerInterface,
    arabcoders\errors\Output\Interfaces\OutputInterface,
    arabcoders\errors\Logging\Interfaces\LoggingInterface,
    arabcoders\errors\Interfaces\MapInterface;

class Error implements ErrorInterface
{
    /**
     * Holds instances of {@see SpecialCaseInterface}
     *
     * @var array
     */
    private $specialCases = [];

    /**
     * Holds instances of logging services.
     *
     * @var LoggingInterface[]
     */
    private $loggingServices = [];

    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var TracerInterface
     */
    private $tracer;
    /**
     * @var StructuredInterface
     */
    private $structured;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var MapInterface
     */
    private $map;

    /**
     * Error constructor.
     *
     * @param array $options
     */
    public function __construct( array $options = [] )
    {
        $this->setTracer( new Tracer() )
             ->setFormatter( new Formatter() )
             ->registerLogger( 'syslog', new Logging\Syslog() )
             ->setOutput( new Output\Basic() )
             ->setStructured( new Structured() )
             ->setMap( new Map() );
    }

    public function setFormatter( FormatterInterface $formatter ) : ErrorInterface
    {
        $this->formatter = $formatter;

        return $this;
    }

    public function getFormatter() : FormatterInterface
    {
        return $this->formatter;
    }

    public function setTracer( TracerInterface $tracer ) : ErrorInterface
    {
        $this->tracer = $tracer;

        return $this;
    }

    public function getTracer() : TracerInterface
    {
        return $this->tracer;
    }

    public function setOutput( OutputInterface $output ) : ErrorInterface
    {
        $this->output = $output;

        return $this;
    }

    public function getOutput() : OutputInterface
    {
        return $this->output;
    }

    public function setStructured( StructuredInterface $structured ) : ErrorInterface
    {
        $this->structured = $structured;

        return $this;
    }

    public function getStructured() : StructuredInterface
    {
        return $this->structured;
    }

    public function setMap( MapInterface $map ) : ErrorInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }

    public function register() : ErrorInterface
    {
        set_error_handler( function ( int $number, string $text, string $file, int $line )
        {
            $this->handleError( $number, $text, $file, $line );
        } );

        register_shutdown_function( function ()
        {
            $error = error_get_last();

            if ( null == $error )
            {
                return;
            }

            $this->handleError( (int) $error['type'], (string) $error['message'], (string) $error['file'], (int) $error['line'] );
        } );

        set_exception_handler( function ( \Throwable $exception )
        {
            $this->handleException( $exception );
        } );

        return $this;
    }

    public function handleError( int $number, string $text, string $file, int $line ) : ErrorInterface
    {
        $error = new ErrorMap( $number, $text, $file, $line );

        $this->getMap()
             ->clear()
             ->setType( self::TYPE_ERROR )
             ->setStructured( $this->getStructured()->setError( $error )->process()->getStructured() )
             ->setTrace( $this->tracer->setIgnore( [ __FILE__ ] )->process()->getTrace() )
             ->setMessage( $this->formatter->formatError( $error ) )
             ->setError( $error )
             ->getInstance();

        if ( array_key_exists( $number, $this->specialCases ) )
        {
            /** @var SpecialCaseInterface $handler */
            foreach ( $this->specialCases[$number] as $handler )
            {
                $handler->setMap( $this->getMap() )->handle();
            }
        }

        $this->log();

        $this->getOutput()
             ->setMap( $this->getMap() )
             ->display();

        return $this;
    }

    public function handleException( \Throwable $exception ) : ErrorInterface
    {
        $this->getMap()->clear()
             ->setType( self::TYPE_EXCEPTION )
             ->setException( $exception )
             ->setStructured( $this->getStructured()->setException( $exception )->process()->getStructured() )
             ->setTrace( $this->tracer->setContext( $exception->getTrace() )->setIgnore( [ __FILE__ ] )->process()->getTrace() )
             ->setMessage( $this->formatter->formatException( $exception ) )
             ->getInstance();

        $name = get_class( $exception );

        if ( array_key_exists( $name, $this->specialCases ) )
        {
            /** @var SpecialCaseInterface $handler */
            foreach ( $this->specialCases[$name] as $handler )
            {
                $handler->setMap( $this->getMap() )->handle();
            }
        }

        $this->log();

        $this->getOutput()
             ->setMap( $this->getMap() )
             ->display();

        return $this;
    }

    public function specialCaseException( string $name, SpecialCaseInterface $handler ) : ErrorInterface
    {
        $className = get_class( $handler );

        if ( !array_key_exists( $name, $this->specialCases ) )
        {
            $this->specialCases[$name] = [];
        }

        if ( array_key_exists( $className, $this->specialCases[$name] ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) class already registered to handle (%s) Exception.', $className, $name ) );
        }

        $this->specialCases[$name][$className] = $handler;

        return $this;
    }

    public function specialCaseError( int $number, SpecialCaseInterface $handler ) : ErrorInterface
    {
        $className = get_class( $handler );

        if ( !array_key_exists( $number, $this->specialCases ) )
        {
            $this->specialCases[$number] = [];
        }

        if ( array_key_exists( $className, $this->specialCases[$number] ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) class already registered to handle (%s) errors.', $className, self::ERROR_CODES[$number] ) );
        }

        $this->specialCases[$number][$className] = $handler;

        return $this;
    }

    public function registerLogger( string $name, LoggingInterface $logger ) : ErrorInterface
    {
        $this->loggingServices[$name] = $logger;

        return $this;
    }

    public function removeLogger( string $name ) : ErrorInterface
    {
        if ( array_key_exists( $name, $this->loggingServices ) )
        {
            unset( $this->loggingServices[$name] );
        }

        return $this;
    }

    private function log() : ErrorInterface
    {
        foreach ( $this->loggingServices as $serviceName => $logger )
        {
            $logger->clear()
                   ->setMap( $this->getMap() )
                   ->process();
        }

        return $this;
    }
}