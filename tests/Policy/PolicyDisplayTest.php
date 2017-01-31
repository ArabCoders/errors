<?php

namespace arabcoders\errors\tests\Policy;

use arabcoders\errors\Policy;

class PolicyDisplayTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnTypeTrue()
    {
        $this->assertEquals( true, ( new Policy( 0, 'test', false, true, false ) )->allowDisplaying() );
    }

    public function testReturnTypeFalse()
    {
        $this->assertEquals( false, ( new Policy( 0, 'test', false, false, false ) )->allowDisplaying() );
    }
}