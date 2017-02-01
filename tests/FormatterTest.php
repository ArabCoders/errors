<?php

use arabcoders\errors\ErrorMap;
use arabcoders\errors\Formatter;
use arabcoders\errors\Interfaces\ErrorInterface;

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Formatter
     */
    private static $instance;

    /**
     * @var \Throwable
     */
    private static $testException;

    /**
     * @var ErrorMap
     */

    private static $testError;

    public function setUp()
    {
        self::$instance      = new Formatter();
        self::$testException = new RuntimeException( 'test', 0 );
        self::$testError     = new ErrorMap( E_ERROR, 'test', basename( __FILE__ ), 1 );
    }

    public function testFormatErrorFailureOnNonErrorMap()
    {
        $this->expectException( TypeError::class );

        self::$instance->formatError( self::$testException );
    }

    public function testFormatExceptionFailureOnNonThrowable()
    {
        $this->expectException( TypeError::class );

        self::$instance->formatException( self::$testError );
    }

    public function testFormatErrorReturn()
    {
        $instance = self::$instance;
        $map      = self::$testError;

        $expected = sprintf( $instance::FORMAT_ERROR,
                             ErrorInterface::ERROR_CODES[$map->getNumber()] ?? $map->getNumber(),
                             $map->getFile(),
                             $map->getLine(),
                             $map->getMessage(),
                             $_SERVER['REQUEST_METHOD'] ?? '',
                             $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF']  ?? ''
        );

        $this->assertEquals( $expected, $instance->formatError( $map ) );
    }

    public function testFormatExceptionReturn()
    {
        $instance = self::$instance;
        $map      = self::$testException;

        $expected = sprintf( $instance::FORMAT_EXCEPTION,
                             get_class( $map ),
                             $map->getFile(),
                             $map->getLine(),
                             $map->getMessage() ? sprintf( 'with message (%s)', $map->getMessage() ) : '',
                             $_SERVER['REQUEST_METHOD'] ?? '',
                             $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? ''
        );

        $this->assertEquals( $expected, $instance->formatException( $map ) );
    }

}