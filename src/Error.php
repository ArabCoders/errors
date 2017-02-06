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

use arabcoders\errors\
{
    Interfaces\ErrorMapInterface, Interfaces\FormatterInterface, Interfaces\SpecialCaseInterface,
    Interfaces\ErrorInterface, Interfaces\StructuredInterface, Interfaces\TracerInterface,
    Output\Interfaces\OutputInterface, Logging\Interfaces\LoggingInterface, Interfaces\MapInterface,
    Interfaces\PolicyInterface
};

class Error implements ErrorInterface
{
    /**
     * Holds instances of {@see SpecialCaseInterface}
     *
     * @var array
     */
    protected $specialCases = [];

    /**
     * Holds instances of logging services.
     *
     * @var LoggingInterface[]
     */
    protected $loggingServices = [];

    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * @var TracerInterface
     */
    protected $tracer;
    /**
     * @var StructuredInterface
     */
    protected $structured;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var MapInterface
     */
    protected $map;

    /**
     * @var PolicyInterface[][]
     */
    protected $policies = [];

    /**
     * Error constructor.
     *
     * @param bool  $default
     * @param array $options
     */
    public function __construct( bool $default = false, array $options = [] )
    {
        $this->setMap( new Map() )
             ->setTracer( new Tracer() )
             ->setFormatter( new Formatter() )
             ->setStructured( new Structured() );

        if ( $default )
        {
            $this->setupDefault();
        }
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
            $this->handleError( new ErrorMap( $number, $text, $file, $line ) );
        } );

        register_shutdown_function( function ()
        {
            $error = error_get_last();

            if ( null == $error || !in_array( $error['type'], self::FATAL_ERRORS ) )
            {
                return;
            }

            $this->handleError( new ErrorMap( (int) $error['type'], (string) $error['message'], (string) $error['file'], (int) $error['line'] ) );
        } );

        set_exception_handler( function ( \Throwable $exception )
        {
            $this->handleException( $exception );
        } );

        return $this;
    }

    public function handleError( ErrorMapInterface $error ) : ErrorInterface
    {
        // error was suppressed with the @-operator
        if ( 0 === error_reporting() && !in_array( $error->getNumber(), self::FATAL_ERRORS ) )
        {
            return $this;
        }

        $this->getMap()
             ->clear()
             ->setType( self::TYPE_ERROR )
             ->setStructured( $this->getStructured()->setError( $error )->process()->getStructured() )
             ->setTrace( $this->tracer->setIgnore( [ __FILE__ ] )->process()->getTrace() )
             ->setMessage( $this->formatter->formatError( $error ) )
             ->setError( $error )
             ->getInstance();

        if ( array_key_exists( $error->getNumber(), $this->specialCases ) )
        {
            /** @var SpecialCaseInterface $handler */
            foreach ( $this->specialCases[$error->getNumber()] as $handler )
            {
                $handler->setMap( $this->getMap() )->handle();
            }
        }

        $this->handleState();

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

        $this->handleState();

        return $this;
    }

    public function addSpecialCase( $parameter, string $name, SpecialCaseInterface $handler ) : ErrorInterface
    {
        if ( !array_key_exists( $parameter, $this->specialCases ) )
        {
            $this->specialCases[$parameter] = [];
        }

        $this->specialCases[$parameter][$name] = $handler;

        return $this;
    }

    public function deleteSpecialCase( $parameter, string $name ) : ErrorInterface
    {
        if ( !array_key_exists( $parameter, $this->specialCases ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) has no specialCases Registered.', $parameter ) );
        }

        if ( !array_key_exists( $name, $this->specialCases[$parameter] ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) has no registered specialCases of name (%s).', $parameter, $name ) );
        }

        unset( $this->specialCases[$parameter][$name] );

        return $this;
    }

    public function addLogger( string $name, LoggingInterface $logger ) : ErrorInterface
    {
        $this->loggingServices[$name] = $logger;

        return $this;
    }

    public function deleteLogger( string $name ) : ErrorInterface
    {
        if ( !array_key_exists( $name, $this->loggingServices ) )
        {
            throw new \InvalidArgumentException( sprintf( 'No Logger Service of name (%s) registered.', $name ) );
        }

        unset( $this->loggingServices[$name] );

        return $this;
    }

    public function addPolicy( string $name, PolicyInterface $policy ) : ErrorInterface
    {
        $this->policies[$policy->getParameter()][$name] = $policy;

        return $this;
    }

    public function deletePolicy( $parameter, string $name ) : ErrorInterface
    {

        if ( !array_key_exists( $parameter, $this->policies ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) has no registered Policies.', $parameter ) );
        }

        if ( !array_key_exists( $name, $this->policies[$parameter] ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) has no registered Policy of name (%s).', $parameter, $name ) );
        }

        unset( $this->policies[$parameter][$name] );

        return $this;
    }

    /**
     * Handle Logging, displaying and exiting of the program.
     */
    protected function handleState()
    {
        if ( $this->getMap()->getType() === self::TYPE_ERROR )
        {
            $parameter = $this->getMap()->getError()->getNumber();
        }
        else
        {

            $parameter = get_class( $this->getMap()->getException() );
        }

        $this->log( $parameter );
        $this->display( $parameter );
        $this->exit( $parameter );
    }

    /**
     * Log Error.
     *
     * @param string|int $parameter
     *
     * @return ErrorInterface
     */
    protected function log( $parameter ) : ErrorInterface
    {

        if ( array_key_exists( $parameter, $this->policies ) )
        {
            foreach ( $this->policies[$parameter] as $policy )
            {
                if ( !$policy->allowLogging() )
                {
                    return $this;
                }
            }
        }

        foreach ( $this->loggingServices as $serviceName => $logger )
        {
            $logger->clear()
                   ->setMap( $this->getMap() )
                   ->process();
        }

        return $this;
    }

    /**
     * Display Error.
     *
     * @param string|int $parameter
     *
     * @return ErrorInterface
     */
    protected function display( $parameter ) : ErrorInterface
    {

        if ( array_key_exists( $parameter, $this->policies ) )
        {
            foreach ( $this->policies[$parameter] as $policy )
            {
                if ( !$policy->allowDisplaying() )
                {
                    return $this;
                }
            }
        }

        $this->getOutput()
             ->setMap( $this->getMap() )
             ->display();

        return $this;
    }

    /**
     * Exit Program On Failure.
     *
     * @param string|int $parameter
     *
     * @return ErrorInterface
     */
    protected function exit( $parameter ) : ErrorInterface
    {

        if ( array_key_exists( $parameter, $this->policies ) )
        {
            foreach ( $this->policies[$parameter] as $policy )
            {
                if ( !$policy->allowExiting() )
                {
                    return $this;
                }
            }
        }

        exit( 1 );
    }

    /**
     * Setup Defaults.
     *
     * @return ErrorInterface
     */
    protected function setupDefault() : ErrorInterface
    {
        $this->addLogger( 'default', new Logging\Syslog() )
             ->setOutput( ( ( 'cli' === PHP_SAPI ) ? new Output\CLI() : new Output\HTML() ) );

        return $this;
    }
}