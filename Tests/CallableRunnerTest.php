<?php
namespace ArgumentResolver\Tests;

use ArgumentResolver\ArgumentResolverFactory;
use ArgumentResolver\CallableRunner;

class CallableRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CallableRunner
     */
    private $runner;

    /**
     *
     */
    protected function setUp()
    {
        $this->runner = new CallableRunner(ArgumentResolverFactory::create());
    }

    public function testCallableIsCalled()
    {
        $testClassProphet = $this->prophesize('SRIO\ArgumentResolver\Tests\Fixtures\TestClass');
        $testClassProphet->namedParametersMethod(1, 2)->shouldBeCalled();
        $testClass = $testClassProphet->reveal();

        $this->runner->run([$testClass, 'namedParametersMethod'], [
            'bar' => 2,
            'foo' => 1
        ]);
    }
}
