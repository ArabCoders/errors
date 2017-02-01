<?php

use arabcoders\errors\Tracer;

class TracerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Tracer
     */
    private static $instance;

    public function setUp()
    {
        self::$instance = new Tracer();
    }

    public function testSetIgnoreReturnTypeFailure()
    {
        $this->expectException( TypeError::class );

        self::$instance->setIgnore( (object) [] );
    }

    public function testSetIgnoreReturnTypeSuccess()
    {
        $this->assertEquals( self::$instance, self::$instance->setIgnore( [ __FILE__ ] ) );
    }

    public function testSetContextReturnTypeFailure()
    {
        $this->expectException( TypeError::class );

        self::$instance->setContext( (object) [] );
    }

    public function testSetContextReturnTypeSuccess()
    {
        $this->assertEquals( self::$instance, self::$instance->setContext( [] ) );
    }

    public function testGetTraceReturnType()
    {
        $this->assertEquals( [], self::$instance->getTrace() );
    }

    public function testClearReturnType()
    {
        $this->assertEquals( self::$instance, self::$instance->clear() );
    }

    public function testProcessReturnType()
    {
        $this->assertEquals( self::$instance, self::$instance->process() );
    }

    public function testGetTraceValueAfterClearIsCalled()
    {
        $this->assertEquals( [], self::$instance->process()->clear()->getTrace() );
    }

    public function testGetTraceWithContext()
    {
        $context = [
            0 => [
                'file'     => __FILE__,
                'line'     => 1,
                'type'     => '',
                'function' => 'require_once',
                'args'     => [
                    __FILE__
                ],
            ],
        ];

        $expected = [
            0 => [
                'file' => __FILE__,
                'line' => 1,
                'call' => "require_once('" . __FILE__ . "');"
            ],
        ];

        $this->assertEquals( $expected, self::$instance->setContext( $context )->process()->getTrace() );
    }

    public function testGetTraceWithContextWithRoot()
    {
        $context = [
            0 => [
                'file'     => __FILE__,
                'line'     => 1,
                'type'     => '',
                'function' => 'require_once',
                'args'     => [
                    __FILE__
                ],
            ],
        ];

        $expectedFile = 'errors/tests/' . basename( __FILE__ );

        $expected = [
            0 => [
                'file' => $expectedFile,
                'line' => 1,
                'call' => "require_once('{$expectedFile}');"
            ],
        ];

        $this->assertEquals( $expected, self::$instance->setRoot( __DIR__ . '/../../' )->setContext( $context )->process()->getTrace() );
    }

    public function testSetRootFailureOnNonString()
    {
        $this->expectException( TypeError::class );
        self::$instance->setRoot( [] );
    }

    public function testSetRootReturnTypeOnSuccess()
    {
        $this->assertEquals( self::$instance, self::$instance->setRoot( 'test' ) );
    }

    public function testGetRootOnEmpty()
    {
        $this->assertEquals( '', self::$instance->getRoot() );
    }

    public function testGetRootOnHasValue()
    {
        $this->assertEquals( __DIR__, self::$instance->setRoot( __DIR__ )->getRoot() );
    }

}