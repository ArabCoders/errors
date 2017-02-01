<?php

use arabcoders\errors\Policy;

class PolicyClosureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Policy
     */
    private static $hasClosure;

    /**
     * @var Policy
     */
    private static $noClosure;

    public function setUp()
    {
        self::$hasClosure = new Policy( 0, 'test', false, false, false, function () : int
        {
            return 1;
        } );

        self::$noClosure = new Policy( 0, 'test', false, false, false );
    }

    public function testClosureReturn()
    {
        $closure = self::$hasClosure->getClosure();

        $this->assertEquals( 1, $closure() );
    }

    public function testFailOnEmptyClosure()
    {
        $this->expectException( \RuntimeException::class );

        self::$noClosure->getClosure();
    }

    public function testReturnTypeOfHasClosure()
    {
        $this->assertTrue( self::$hasClosure->hasClosure() );
        $this->assertFalse( self::$noClosure->hasClosure() );
    }

    /**
     * int $type, $parameter, bool $logging, bool $displaying, bool $exiting, \Closure $closure = null
     */
    public function testTypeType()
    {
        $this->expectException( \TypeError::class );

        new Policy( 'foo', 'test', false, false, false, null );
    }

    public function testParameterType()
    {
        $this->expectException( \InvalidArgumentException::class );
        new Policy( 0, [], false, false, false, null );
    }

    public function testLoggingParameter()
    {
        $this->expectException( \TypeError::class );
        new Policy( 0, 'test', [], false, false, null );
    }

    public function testDisplayParameter()
    {
        $this->expectException( \TypeError::class );
        new Policy( 0, E_ERROR, false, [], false, null );
    }

    public function testExitingParameter()
    {
        $this->expectException( \TypeError::class );
        new Policy( 0, 'test', false, false, false, [] );
    }

    public function testClosureParameter()
    {
        $this->expectException( \TypeError::class );
        new Policy( 0, 'test', false, false, false, [] );
    }

    public function testReturnTypeTrue()
    {
        $this->assertTrue( ( new Policy( 0, 'test', false, true, false ) )->allowDisplaying() );
    }

    public function testReturnTypeFalse()
    {
        $this->assertFalse( ( new Policy( 0, 'test', false, false, false ) )->allowDisplaying() );
    }

    public function testAllowExitingTypeTrue()
    {
        $this->assertTrue( ( new Policy( 0, 'test', false, false, true ) )->allowExiting() );
    }

    public function testAllowExitingTypeFalse()
    {
        $this->assertFalse( ( new Policy( 0, 'test', false, false, false ) )->allowExiting() );
    }

    public function testReturnTypeOfisOfTypeTrue()
    {
        $this->assertTrue( ( new Policy( 0, 'test', false, false, false ) )->isOfType( 0 ) );
        $this->assertTrue( ( new Policy( 1, 'test', false, false, false ) )->isOfType( 1 ) );

    }

    public function testReturnTypeOfisOfTypeFalse()
    {
        $this->assertFalse( ( new Policy( 1, 'test', false, false, false ) )->isOfType( 0 ) );
        $this->assertFalse( ( new Policy( 0, 'test', false, false, false ) )->isOfType( 1 ) );
    }

    public function testAllowLoggingTypeTrue()
    {
        $this->assertTrue( ( new Policy( 0, 'test', true, false, false ) )->allowLogging() );
    }

    public function testAllowLoggingTypeFalse()
    {
        $this->assertFalse( ( new Policy( 0, 'test', false, false, false ) )->allowLogging() );
    }

    public function testGetParameterReturn()
    {
        $this->assertEquals( 'test', ( new Policy( 0, 'test', false, false, false ) )->getParameter() );
    }
}