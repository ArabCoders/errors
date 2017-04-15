<?php

use arabcoders\errors\ErrorMap;
use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Structured;

class StructuredTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Structured
     */
    private static $instance;

    public function setUp()
    {
        self::$instance = new Structured();
    }

    public function testSetErrorFailureOnNonErrorMap()
    {
        $this->expectException( TypeError::class );

        self::$instance->setError( self::$instance );
    }

    public function testSetExceptionFailureOnNonErrorMap()
    {
        $this->expectException( TypeError::class );

        self::$instance->setException( self::$instance );
    }

    public function testSetMessageFailureOnNonString()
    {
        $this->expectException( TypeError::class );

        self::$instance->setMessage( [] );
    }

    public function testSetErrorReturnTypeOnSuccess()
    {
        $map = new ErrorMap( E_WARNING, 'test', basename( __FILE__ ), 2 );

        $this->assertEquals( self::$instance, self::$instance->setError( $map ) );
    }

    public function testSetExceptionReturnTypeOnSuccess()
    {
        $map = new Exception( 'test', 0 );

        $this->assertEquals( self::$instance, self::$instance->setException( $map ) );
    }

    public function testClearReturn()
    {
        $error = new ErrorMap( E_WARNING, 'test', basename( __FILE__ ), 2 );
        $map   = new Exception( 'test', 0 );
        self::$instance->setError( $error )->setException( $map )->process()->clear();

        $this->assertEquals( [], self::$instance->getStructured() );
    }

    public function testProcessReturnType()
    {
        $this->assertEquals( self::$instance, self::$instance->process() );
    }

    public function testGetStructuredReturn()
    {
        $error = new ErrorMap( E_WARNING, 'test', basename( __FILE__ ), 2 );
        $e     = new Exception( 'test', 0 );

        $structured = [
            'error'     => [
                'errorType' => ErrorInterface::ERROR_CODES[$error->getNumber()] ?? $error->getNumber(),
                'errorCode' => $error->getNumber(),
                'file'      => $error->getFile(),
                'line'      => $error->getLine(),
                'message'   => $error->getMessage(),
            ],
            'exception' => [
                'type'    => get_class( $e ),
                'code'    => $e->getCode(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage(),
            ],
            'request'   => [
                'domain' => strtolower( $_SERVER['HTTP_SERVER'] ?? $_SERVER['HTTP_HOST'] ?? null ),
                'method' => $_SERVER['REQUEST_METHOD'] ?? null,
                'uri'    => $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? null,
                'refer'  => $_SERVER['HTTP_REFERER'] ?? null,
            ],
            'trigger'   => [
                'ip'    => $_SERVER['REMOTE_ADDR'] ?? null,
                'agent' => $_SERVER['HTTP_USER_AGENT']  ?? null,
            ],
        ];

        self::$instance->setError( $error )->setException( $e )->process();

        $this->assertEquals( $structured, self::$instance->getStructured() );
    }

}