<?php

namespace arabcoders\errors\tests\Policy;

use arabcoders\errors\Policy;

class PolicyParameterTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnTypeTrue()
    {
        $this->assertEquals( 'test', ( new Policy( 0, 'test', false, false, false ) )->getParameter() );
    }
}