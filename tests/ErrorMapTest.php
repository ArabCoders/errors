<?php

use arabcoders\errors\ErrorMap;

class ErrorMapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ErrorMap
     */
    private static $instance;

    public function setUp()
    {
        self::$instance = new ErrorMap( E_ERROR, 'test', __FILE__, 1 );
    }

    public function testConstructorTypeAsString()
    {
        $this->expectException( TypeError::class );

        new ErrorMap( 'string', 'message', __FILE__, __LINE__ );
    }


    public function testConstructorMessageAsArray()
    {
        $this->expectException( TypeError::class );

        new ErrorMap( E_WARNING, [], __FILE__, __LINE__ );
    }

    public function testConstructorFileAsArray()
    {
        $this->expectException( TypeError::class );

        new ErrorMap( E_WARNING, 'test', [], __LINE__ );
    }

    public function testConstructorLineAsArray()
    {
        $this->expectException( TypeError::class );

        new ErrorMap( E_WARNING, 'test', __FILE__, [] );
    }

    public function testGetNumber()
    {
        $this->assertEquals( E_ERROR, self::$instance->getNumber() );
    }

    public function testGetMessage()
    {
        $this->assertEquals( 'test', self::$instance->getMessage() );
    }

    public function testGetFile()
    {
        $this->assertEquals( __FILE__, self::$instance->getFile() );
    }

    public function testGetLine()
    {
        $this->assertEquals( 1, self::$instance->getLine() );
    }
}