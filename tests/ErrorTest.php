<?php

use arabcoders\errors\Error;
use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\FormatterInterface;
use arabcoders\errors\Interfaces\ListenerInterface;
use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Interfaces\PolicyInterface;
use arabcoders\errors\Interfaces\StructuredInterface;
use arabcoders\errors\Interfaces\TracerInterface;
use arabcoders\errors\Logging\Interfaces\LoggingInterface;
use arabcoders\errors\Output\Basic;
use arabcoders\errors\Output\Interfaces\OutputInterface;

class ErrorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Error
     */
    private static $i;

    public function setUp()
    {
        self::$i = new Error();
    }

    public function testSetFormatterReturnTypeOnSuccess()
    {
        $this->assertEquals( self::$i, self::$i->setFormatter( new \arabcoders\errors\Formatter() ) );
    }

    public function testSetFormatterOnFailure()
    {
        $this->expectException( TypeError::class );
        $this->assertEquals( self::$i, self::$i->setFormatter( self::$i ) );
    }

    public function testSetTracerReturnTypeOnSuccess()
    {
        $this->assertEquals( self::$i, self::$i->setTracer( new \arabcoders\errors\Tracer() ) );
    }

    public function testSetTracerOnFailure()
    {
        $this->expectException( TypeError::class );
        $this->assertEquals( self::$i, self::$i->setTracer( self::$i ) );
    }

    public function testSetOutputReturnTypeOnSuccess()
    {
        $this->assertEquals( self::$i, self::$i->setOutput( new \arabcoders\errors\Output\CLI() ) );
    }

    public function testSetOutputOnFailure()
    {
        $this->expectException( TypeError::class );
        $this->assertEquals( self::$i, self::$i->setOutput( self::$i ) );
    }

    public function testSetStructuredReturnTypeOnSuccess()
    {
        $this->assertEquals( self::$i, self::$i->setStructured( new \arabcoders\errors\Structured() ) );
    }

    public function testSetStructuredOnFailure()
    {
        $this->expectException( TypeError::class );
        $this->assertEquals( self::$i, self::$i->setStructured( self::$i ) );
    }

    public function testSetMapReturnTypeOnSuccess()
    {
        $this->assertEquals( self::$i, self::$i->setMap( new \arabcoders\errors\Map() ) );
    }

    public function testSetMapOnFailure()
    {
        $this->expectException( TypeError::class );
        $this->assertEquals( self::$i, self::$i->setMap( self::$i ) );
    }

    public function testGetFormatterReturnType()
    {
        $this->assertInstanceOf( FormatterInterface::class, self::$i->getFormatter() );
    }

    public function testGetTracerReturnType()
    {
        $this->assertInstanceOf( TracerInterface::class, self::$i->getTracer() );
    }

    public function testGetOutputReturnTypeOnEmpty()
    {
        $this->expectException( TypeError::class );
        self::$i->getOutput();
    }

    public function testGetOutputReturnType()
    {
        $this->assertInstanceOf( OutputInterface::class, self::$i->setOutput( new Basic() )->getOutput() );
    }

    public function testGetOutputReturnTypeDefault()
    {
        $i = new Error( true );
        $this->assertInstanceOf( OutputInterface::class, $i->getOutput() );
    }

    public function testGetStructuredReturnType()
    {
        $this->assertInstanceOf( StructuredInterface::class, self::$i->getStructured() );
    }

    public function testGetMapReturnType()
    {
        $this->assertInstanceOf( MapInterface::class, self::$i->getMap() );
    }

    public function testRegisterReturnType()
    {
        $this->assertEquals( self::$i, self::$i->register() );
    }

    public function testAddListenerException()
    {
        $this->assertEquals( self::$i, self::$i->addListener( PDOException::class, 'PDO', new ListenerTest() ) );
    }

    public function testAddListenerError()
    {
        $this->assertEquals( self::$i, self::$i->addListener( ErrorInterface::FATAL_ERRORS[\E_ERROR], 'E-Error', new ListenerTest() ) );
    }

    public function testListenerBeingCalled()
    {
        $listener = new ListenerTest();

        self::$i->addListener( LogicException::class, 'PDO', $listener );
        self::$i->setOutput( new nullOutputTest() );
        self::$i->addPolicy(
            LogicException::class,
            new \arabcoders\errors\Policy( ErrorInterface::TYPE_EXCEPTION, LogicException::class, false, false, false )
        );

        self::$i->handleException( new LogicException( 'foo' ) );
        $this->assertEquals( true, $listener->called );
        $this->assertEquals( self::$i->getMap(), $listener->getMap() );
    }

    public function testDeleteListenerNoSubSection()
    {
        $this->expectException( InvalidArgumentException::class );

        self::$i->deleteListener( LogicException::class, 'foo' );
    }

    public function testDeleteSpecialCaseNoCase()
    {
        $this->expectException( InvalidArgumentException::class );

        self::$i->addListener( LogicException::class, 'foo', new ListenerTest() );

        self::$i->deleteListener( LogicException::class, 'bar' );
    }

    public function testDeleteSpecialCase()
    {
        self::$i->addListener( LogicException::class, 'foo', new ListenerTest() );

        $this->assertEquals( self::$i, self::$i->deleteListener( LogicException::class, 'foo' ) );
    }

    public function testAddLoggerSuccess()
    {
        $this->assertEquals( self::$i, self::$i->addLogger( 'logger', new LoggerTest() ) );
    }

    public function testAddLoggerFail()
    {
        $this->expectException( TypeError::class );

        $this->assertEquals( self::$i, self::$i->addLogger( 'logger', self::$i ) );
    }

    public function testDeleteLoggerSuccess()
    {
        $this->assertEquals( self::$i, self::$i->addLogger( 'logger', new LoggerTest() ) );
    }

    public function testDeleteLoggerFail()
    {
        $this->expectException( \InvalidArgumentException::class );

        self::$i->deleteLogger( 'bar' );
    }

    public function testLoggerBeingCalled()
    {
        $logger = new LoggerTest();

        self::$i->addLogger( LoggerTest::class, $logger );
        self::$i->setOutput( new nullOutputTest() );
        self::$i->addPolicy(
            LogicException::class,
            new \arabcoders\errors\Policy( ErrorInterface::TYPE_EXCEPTION, LogicException::class, true, false, false )
        );

        self::$i->handleException( new LogicException( 'foo' ) );
        $this->assertEquals( true, $logger->called );
        $this->assertEquals( self::$i->getMap(), $logger->getMap() );
    }


    public function testAddPolicySuccess()
    {
        $this->assertEquals( self::$i,
                             self::$i->addPolicy(
                                 LogicException::class,
                                 new PolicyTest( ErrorInterface::TYPE_EXCEPTION, LogicException::class, false, false, false ) )
        );

    }

    public function testAddPolicyFail()
    {
        $this->expectException( TypeError::class );
        $this->assertEquals( self::$i, self::$i->addPolicy( LogicException::class, self::$i ) );
    }

    public function testDeleteDeletePolicyNoSubSection()
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( sprintf( '(%s) has no registered Policies.', LogicException::class ) );

        self::$i->deletePolicy( LogicException::class, 'foo' );
    }

    public function testDeletePolicyNoPolicy()
    {
        $this->expectException( InvalidArgumentException::class );
        $this->expectExceptionMessage( sprintf( '(%s) has no registered Policy of name (%s).', LogicException::class, 'bar' ) );

        self::$i->addPolicy( LogicException::class,
                             new PolicyTest( ErrorInterface::TYPE_EXCEPTION, LogicException::class, false, false, false )
        );

        self::$i->deletePolicy( LogicException::class, 'bar' );
    }

    public function testPolicyBeingCalled()
    {
        self::$i->setOutput( new nullOutputTest() )
                ->addPolicy( LogicException::class, new PolicyTest( ErrorInterface::TYPE_EXCEPTION, LogicException::class, false, false, false ) )
                ->addPolicy( InvalidArgumentException::class, new PolicyTest( ErrorInterface::TYPE_EXCEPTION, InvalidArgumentException::class, true, true, false ) );

        try
        {
            throw new LogicException( 'foo' );
        }
        catch ( Throwable $e )
        {
            $this->assertEquals( self::$i, self::$i->handleException( $e ) );
        }

        try
        {
            $this->assertNotEquals( self::$i, self::$i->handleException( new InvalidArgumentException() ) );
        }
        catch ( Throwable $e )
        {
            $this->assertEquals( true, true );
        }
    }
}

class LoggerTest implements LoggingInterface
{
    private $map;

    public $called = false;

    public function process() : LoggingInterface
    {
        $this->called = true;

        return $this;
    }

    public function clear() : LoggingInterface
    {
        $this->map = [];

        return $this;
    }


    public function setMap( MapInterface $map ) : LoggingInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}

class ListenerTest implements ListenerInterface
{
    private $map;

    public $called = false;

    public function handle()
    {
        return $this->called = true;
    }

    public function setMap( MapInterface $map ) : ListenerInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}

class PolicyTest implements PolicyInterface
{
    /**
     * @var int
     */
    private $type;
    /**
     * @var int|string
     */
    private $parameter;
    /**
     * @var bool
     */
    private $logging;
    /**
     * @var bool
     */
    private $displaying;
    /**
     * @var bool
     */
    private $exiting;
    /**
     * @var Closure
     */
    private $closure;

    public function __construct( int $type, $parameter, bool $logging, bool $displaying, bool $exiting, \Closure $closure = null )
    {
        $this->type       = $type;
        $this->parameter  = $parameter;
        $this->logging    = $logging;
        $this->displaying = $displaying;
        $this->exiting    = $exiting;
        $this->closure    = $closure;
    }

    public function isOfType( int $type ) : bool
    {
        return $this->type === $type;
    }

    public function getType() : int
    {
        return $this->type;
    }

    public function getParameter()
    {
        return $this->parameter;
    }

    public function allowLogging() : bool
    {
        return $this->logging;
    }

    public function allowDisplaying() : bool
    {
        return $this->displaying;
    }

    public function allowExiting() : bool
    {
        return $this->exiting;
    }

    public function hasClosure() : bool
    {
        return ( ( $this->closure instanceof \Closure ) );
    }

    public function getClosure() : \Closure
    {
        return $this->closure;
    }
}

class nullOutputTest implements OutputInterface
{
    private $map;

    public function display() : OutputInterface
    {
        return $this;
    }

    public function setMap( MapInterface $map ) : OutputInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}