<?php

use arabcoders\errors\Error;
use arabcoders\errors\Interfaces\FormatterInterface;
use arabcoders\errors\Interfaces\MapInterface;
use arabcoders\errors\Interfaces\PolicyInterface;
use arabcoders\errors\Interfaces\SpecialCaseInterface;
use arabcoders\errors\Interfaces\StructuredInterface;
use arabcoders\errors\Interfaces\TracerInterface;
use arabcoders\errors\Logging\Interfaces\LoggingInterface;
use arabcoders\errors\Output\Basic;
use arabcoders\errors\Output\Interfaces\OutputInterface;

class ErrorTest extends \PHPUnit_Framework_TestCase
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
}

class Logger implements LoggingInterface
{
    private $map;

    public function process() : LoggingInterface
    {
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

class SpecialCase implements SpecialCaseInterface
{
    private $map;

    public function handle()
    {
        // TODO: Implement handle() method.
    }

    public function setMap( MapInterface $map ) : SpecialCaseInterface
    {
        $this->map = $map;

        return $this;
    }

    public function getMap() : MapInterface
    {
        return $this->map;
    }
}

class Policy implements PolicyInterface
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
