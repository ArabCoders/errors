<?php

namespace arabcoders\errors\tests\Policy;

use arabcoders\errors\Policy;

class PolicyIsOfTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnTypeOfisOfTypeTrue()
    {
        $this->assertEquals( true, ( new Policy( 0, 'test', false, false, false ) )->isOfType( 0 ) );
        $this->assertEquals( true, ( new Policy( 1, 'test', false, false, false ) )->isOfType( 1 ) );

    }

    public function testReturnTypeOfisOfTypeFalse()
    {
        $this->assertEquals( false, ( new Policy( 1, 'test', false, false, false ) )->isOfType( 0 ) );
        $this->assertEquals( false, ( new Policy( 0, 'test', false, false, false ) )->isOfType( 1 ) );
    }

}