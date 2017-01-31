<?php

namespace arabcoders\errors\tests\Policy;

use arabcoders\errors\Policy;

class PolicyLogTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnTypeTrue()
    {
        $this->assertEquals( true, ( new Policy( 0, 'test', true, false, false ) )->allowLogging() );
    }

    public function testReturnTypeFalse()
    {
        $this->assertEquals( false, ( new Policy( 0, 'test', false, false, false ) )->allowLogging() );
    }

}