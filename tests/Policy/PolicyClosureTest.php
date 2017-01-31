<?php

namespace arabcoders\errors\tests\Policy;

use arabcoders\errors\Policy;

class PolicyClosureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Policy
     */
    private static $hasClosure;

    /**
     * @var Policy
     */
    private static $noClosure;

    public function setUp()
    {
        self::$hasClosure = new Policy( 0, 'test', false, false, false, function () : int
        {
            return 1;
        } );

        self::$noClosure = new Policy( 0, 'test', false, false, false );
    }

    public function testClosureReturn()
    {
        $closure = self::$hasClosure->getClosure();

        $this->assertEquals( 1, $closure() );
    }

    public function testFailOnEmptyClosure()
    {
        $this->expectException( \RuntimeException::class );

        self::$noClosure->getClosure();
    }

    public function testReturnTypeOfHasClosure()
    {
        $this->assertEquals( true, self::$hasClosure->hasClosure() );
        $this->assertEquals( false, self::$noClosure->hasClosure() );
    }
}