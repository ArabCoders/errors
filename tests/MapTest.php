<?php

use arabcoders\errors\ErrorMap;
use arabcoders\errors\Interfaces\ErrorInterface;
use arabcoders\errors\Interfaces\ErrorMapInterface;
use arabcoders\errors\Map;

class MapTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
    }

    public function testSetType()
    {
        $map = new Map();
        $this->assertEquals( $map, $map->setType( 1 ) );

        $this->expectException( \TypeError::class );

        $map->setType( 'test' );
    }

    public function testGetType()
    {
        $map = new Map();
        $this->assertEquals( 0, $map->getType() );

        $this->assertEquals( 1, $map->setType( 1 )->getType() );
    }

    public function testIsException()
    {
        $map = new Map();

        $map->setType( ErrorInterface::TYPE_ERROR );

        $this->assertFalse( $map->isException() );

        $map->setType( ErrorInterface::TYPE_EXCEPTION );

        $this->assertTrue( $map->isException() );
    }

    public function testIsError()
    {
        $map = new Map();

        $map->setType( ErrorInterface::TYPE_EXCEPTION );

        $this->assertFalse( $map->isError() );

        $map->setType( ErrorInterface::TYPE_ERROR );

        $this->assertTrue( $map->isError() );
    }

    public function testSetTrace()
    {
        $map = new Map();

        $this->expectException( \TypeError::class );

        $map->setTrace( (object) [] );

        $this->assertEquals( $map, $map->setTrace( [ 'test' => 1 ] ) );
    }

    public function testGetTrace()
    {
        $map = new Map();

        $this->assertEquals( [], $map->getTrace() );

        $this->assertEquals( [ 'test' => 1 ], $map->setTrace( [ 'test' => 1 ] )->getTrace() );
    }

    public function testSetMessage()
    {
        $map = new Map();

        $this->assertEquals( $map, $map->setMessage( 'test' ) );

        $this->expectException( \TypeError::class );

        $map->setMessage( [] );
    }

    public function testGetMessage()
    {
        $map = new Map();

        $this->assertEquals( '', $map->getMessage() );
        $this->assertEquals( 'test', $map->setMessage( 'test' )->getMessage() );
    }

    public function testSetStructured()
    {
        $map = new Map();

        $this->expectException( \TypeError::class );

        $map->setStructured( (object) [] );

        $this->assertEquals( $map, $map->setStructured( [ 'test' => 1 ] ) );
    }

    public function testGetStructured()
    {
        $map = new Map();

        $this->assertEquals( [], $map->getStructured() );

        $this->assertEquals( [ 'test' => 1 ], $map->setStructured( [ 'test' => 1 ] )->getStructured() );
    }

    public function testClear()
    {
        $map = new Map();

        $map->setMessage( 'foo' )
            ->setStructured( [ 'test' => 1 ] )
            ->setTrace( [ 'test' => 2 ] )
            ->setType( 1 )
            ->setError( new ErrorMap( \E_ERROR, 'test', __FILE__, __LINE__ ) )
            ->setException( new LogicException( 'test' ) )
            ->clear();

        $this->assertEquals( '', $map->getMessage() );
        $this->assertEquals( [], $map->getStructured() );
        $this->assertEquals( [], $map->getTrace() );
        $this->assertEquals( false, $map->hasException() );
        $this->assertEquals( false, $map->hasError() );
        $this->assertEquals( 0, $map->getType() );
    }

    public function testGetInstance()
    {
        $map = new Map();

        $this->assertEquals( $map, $map->getInstance() );
    }

    public function testSetError()
    {
        $map = new Map();

        $this->expectException( \TypeError::class );

        $map->setError( null );

        $this->assertEquals( $map, $map->setError( new ErrorMap( E_WARNING, 'test', __FILE__, __LINE__ ) ) );
    }

    public function testGetError()
    {
        $map = new Map();

        $this->expectException( \RuntimeException::class );

        $map->getError();

        $map->setError( new ErrorMap( E_WARNING, 'test', __FILE__, __LINE__ ) );

        $this->assertInstanceOf( ErrorMapInterface::class, $map->getError() );
    }

    public function testSetException()
    {
        $map = new Map();

        $this->expectException( \TypeError::class );

        $map->setException( null );

        $this->assertEquals( $map, $map->setException( new \Exception() ) );
    }

    public function testGetException()
    {
        $map = new Map();

        $this->expectException( \RuntimeException::class );

        $map->getException();

        $map->setException( new \Exception() );

        $this->assertInstanceOf( \Throwable::class, $map->getException() );
    }

    public function testSetId()
    {
        $map = new Map();

        $this->assertEquals( $map, $map->setId( sha1( random_bytes( 16 ) ) ) );

        $this->expectException( \TypeError::class );

        $map->setMessage( [] );
    }

    public function testGetId()
    {
        $map = new Map();

        $id = sha1( random_bytes( 16 ) );

        $this->assertEquals( '', $map->getId() );
        $this->assertEquals( $id, $map->setId( $id )->getId() );
    }

}