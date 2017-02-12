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

use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\ErrorMapInterface;
use arabcoders\errors\Interfaces\FormatterInterface;
use arabcoders\errors\Interfaces\ListenerInterface;
use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Interfaces\PolicyInterface;
use arabcoders\errors\Interfaces\StructuredInterface;
use arabcoders\errors\Interfaces\TracerInterface;
use arabcoders\errors\Logging\Interfaces\LoggingInterface;
use arabcoders\errors\Output\Interfaces\OutputInterface;

/**
 * Class Error
 *
 * @package arabcoders\errors
 */
class Error implements ErrorInterface
{
    /**
     * @var ListenerInterface[][] instances of {@see ListenerInterface}
     */
    protected $listener = [];

    /**
     * @var LoggingInterface[] logging services.
     */
    protected $loggers = [];

    /**
     * @var FormatterInterface Message formatter
     */
    protected $formatter;

    /**
     * @var TracerInterface Context tracer.
     */
    protected $tracer;

    /**
     * @var StructuredInterface Structured data mapper.
     */
    protected $structured;

    /**
     * @var OutputInterface Output handler.
     */
    protected $output;

    /**
     * @var MapInterface Map class.
     */
    protected $map;

    /**
     * @var PolicyInterface[][] instances of {@see PolicyInterface}
     */
    protected $policies = [];

    /**
     * Error constructor.
     *
     * @param bool  $default Whether to initialise default logger and output.
     * @param array $options More options right now it does nothing.
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

    /**
     * Set message formatter.
     *
     * @param FormatterInterface $formatter Message formatter.
     *
     * @return ErrorInterface
     */
    public function setFormatter( FormatterInterface $formatter ) : ErrorInterface
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Get formatter.
     *
     * @return FormatterInterface
     */
    public function getFormatter() : FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * Set Tracer.
     *
     * @param TracerInterface $tracer Context tracer.
     *
     * @return ErrorInterface
     */
    public function setTracer( TracerInterface $tracer ) : ErrorInterface
    {
        $this->tracer = $tracer;

        return $this;
    }

    /**
     * Get tracer.
     *
     * @return TracerInterface
     */
    public function getTracer() : TracerInterface
    {
        return $this->tracer;
    }

    /**
     * Set output.
     *
     * @param OutputInterface $output Output handler.
     *
     * @return mixed
     */
    public function setOutput( OutputInterface $output ) : ErrorInterface
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Get output.
     *
     * @return OutputInterface
     */
    public function getOutput() : OutputInterface
    {
        return $this->output;
    }

    /**
     * Set structured mapper.
     *
     * @param StructuredInterface $structured Structured data mapper.
     *
     * @return ErrorInterface
     */
    public function setStructured( StructuredInterface $structured ) : ErrorInterface
    {
        $this->structured = $structured;

        return $this;
    }

    /**
     * Get structured mapper.
     *
     * @return StructuredInterface
     */
    public function getStructured() : StructuredInterface
    {

        return $this->structured;
    }

    /**
     * Set map.
     *
     * @param MapInterface $map Map class.
     *
     * @return ErrorInterface
     */
    public function setMap( MapInterface $map ) : ErrorInterface
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
     * Register handlers for error/shutdown/exceptions.
     *
     * @return ErrorInterface
     */
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

            $this->handleError(
                new ErrorMap( (int) $error['type'], (string) $error['message'], (string) $error['file'], (int) $error['line'] )
            );
        } );

        set_exception_handler( function ( \Throwable $exception )
        {
            $this->handleException( $exception );
        } );

        return $this;
    }

    /**
     * Process error.
     *
     * @param ErrorMapInterface $error Error instance.
     *
     * @return ErrorInterface
     */
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

        if ( array_key_exists( $error->getNumber(), $this->listener ) )
        {
            foreach ( $this->listener[$error->getNumber()] as $handler )
            {
                $handler->setMap( $this->getMap() )->handle();
            }
        }

        $this->handleState();

        return $this;
    }

    /**
     * Process exception.
     *
     * @param \Throwable $exception The thrown exception
     *
     * @return ErrorInterface
     */
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

        if ( array_key_exists( $name, $this->listener ) )
        {
            foreach ( $this->listener[$name] as $handler )
            {
                $handler->setMap( $this->getMap() )->handle();
            }
        }

        $this->handleState();

        return $this;
    }

    /**
     * Attach Listener for specific exception or error.
     *
     * @param string|int        $parameter Class FQN or error number.
     * @param string            $name      Name to refer to this Listener.
     * @param ListenerInterface $handler   The initialized handler.
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if parameter is neither string nor int.
     */
    public function addListener( $parameter, string $name, ListenerInterface $handler ) : ErrorInterface
    {
        if ( !is_string( $parameter ) && is_int( $parameter ) )
        {
            throw new \InvalidArgumentException( '$parameter is neither string nor int.' );
        }

        if ( !array_key_exists( $parameter, $this->listener ) )
        {
            $this->listener[$parameter] = [];
        }

        $this->listener[$parameter][$name] = $handler;

        return $this;
    }

    /**
     * Delete listener.
     *
     * @param string|int $parameter Class FQN or error number.
     * @param string     $name      The name that was used in {@see addListener}
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if parameter does not exists or listener name is not registered.
     */
    public function deleteListener( $parameter, string $name ) : ErrorInterface
    {
        if ( !array_key_exists( $parameter, $this->listener ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) has no Listener Registered.', $parameter ) );
        }

        if ( !array_key_exists( $name, $this->listener[$parameter] ) )
        {
            throw new \InvalidArgumentException( sprintf( '(%s) has no registered Listener of name (%s).', $parameter, $name ) );
        }

        unset( $this->listener[$parameter][$name] );

        return $this;
    }

    /**
     * Add logging service.
     *
     * @param string           $name   Name to refer to this logging service.
     * @param LoggingInterface $logger The initialized handler.
     *
     * @return ErrorInterface
     */
    public function addLogger( string $name, LoggingInterface $logger ) : ErrorInterface
    {
        $this->loggers[$name] = $logger;

        return $this;
    }

    /**
     * Delete logging service.
     *
     * @param string $name The name that was used in {@see addLogger}
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if logger name is not registered.
     */
    public function deleteLogger( string $name ) : ErrorInterface
    {
        if ( !array_key_exists( $name, $this->loggers ) )
        {
            throw new \InvalidArgumentException( sprintf( 'No Logger Service of name (%s) registered.', $name ) );
        }

        unset( $this->loggers[$name] );

        return $this;
    }

    /**
     * Add policy.
     *
     * @param string          $name   Name to refer to this policy.
     * @param PolicyInterface $policy The initialized policy.
     *
     * @return ErrorInterface
     */
    public function addPolicy( string $name, PolicyInterface $policy ) : ErrorInterface
    {
        $this->policies[$policy->getParameter()][$name] = $policy;

        return $this;
    }

    /**
     * Delete policy.
     *
     * @param string|int $parameter Class FQN or error number.
     * @param string     $name      The name that was used in {@see addPolicy}
     *
     * @return ErrorInterface
     * @throws \InvalidArgumentException if parameter does not exists or policy name is not registered.
     */
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
     * @param string|int $parameter Class FQN or error number.
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

        foreach ( $this->loggers as $serviceName => $logger )
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
     * @param string|int $parameter Class FQN or error number.
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
     * @param string|int $parameter Class FQN or error number.
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