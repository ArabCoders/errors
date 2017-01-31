<?php

namespace arabcoders\errors\tests\Policy;

use arabcoders\errors\Policy;

class PolicyConstructorTypesTest extends \PHPUnit_Framework_TestCase
{
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
}