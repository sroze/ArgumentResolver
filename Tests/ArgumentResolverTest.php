<?php
namespace SRIO\ArgumentResolver\Tests;

use SRIO\ArgumentResolver\ArgumentResolver;
use SRIO\ArgumentResolver\ArgumentResolverFactory;
use SRIO\ArgumentResolver\Tests\Fixtures\TestClass;
use SRIO\ArgumentResolver\Tests\Fixtures\TestValueObject;

class ArgumentResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArgumentResolver
     */
    private $argumentResolver;

    protected function setUp()
    {
        $this->argumentResolver = ArgumentResolverFactory::create();
    }

    public function testOneObjectArgument()
    {
        $object = new TestValueObject();
        $arguments = $this->argumentResolver->resolveArguments([new TestClass(), 'valueObjectMethod'], [
            'foo' => 'bar',
            'value' => $object
        ]);

        $this->assertEquals($arguments, [$object]);
    }

    /**
     * @expectedException \SRIO\ArgumentResolver\Exception\ResolutionException
     */
    public function testTypeObjectArgumentsAndDifferentScalarName()
    {
        $this->argumentResolver->resolveArguments([new TestClass(), 'objectAndScalarMethod'], [
            'foo' => 'bar',
            'value' => new TestValueObject()
        ]);
    }

    public function testTypeObjectArgumentsAndSameScalarName()
    {
        $object = new TestValueObject();
        $arguments = $this->argumentResolver->resolveArguments([new TestClass(), 'objectAndScalarMethod'], [
            'count' => 2,
            'value' => $object
        ]);

        $this->assertEquals($arguments, [$object, 2]);
    }

    /**
     * @expectedException \SRIO\ArgumentResolver\Exception\ResolutionException
     */
    public function testNoObjectOfTypeFound()
    {
        $this->argumentResolver->resolveArguments([new TestClass(), 'objectAndScalarMethod'], [
            'foo' => 'bar',
            'value' => new TestClass()
        ]);
    }

    /**
     * @expectedException \SRIO\ArgumentResolver\Exception\ResolutionException
     */
    public function testMissingArrayArgument()
    {
        $this->argumentResolver->resolveArguments([new TestClass(), 'arrayMethod'], [
            'foo' => 'bar',
            'value' => new TestValueObject()
        ]);
    }

    public function testArrayArgument()
    {
        $arguments = $this->argumentResolver->resolveArguments([new TestClass(), 'arrayMethod'], [
            'foo' => ['ok']
        ]);

        $this->assertEquals([['ok']], $arguments);
    }

    public function testNamedParameters()
    {
        $arguments = $this->argumentResolver->resolveArguments([new TestClass(), 'namedParametersMethod'], [
            'bar' => 1,
            'foo' => 2
        ]);

        $this->assertEquals([2, 1], $arguments);
    }

    public function testNamedParametersOfDifferentTypes()
    {
        $arguments = $this->argumentResolver->resolveArguments([new TestClass(), 'namedParametersMethod'], [
            'bar' => '1',
            'foo' => 2
        ]);

        $this->assertEquals([2, '1'], $arguments);
    }

    /**
     * @expectedException \SRIO\ArgumentResolver\Exception\ResolutionException
     */
    public function testTwoValueObjectsWithDifferentArgumentNames()
    {
        $this->argumentResolver->resolveArguments([new TestClass(), 'twoValueObjectsMethod'], [
            'foo' => 'bar',
            'a' => new TestValueObject(),
            'b' => new TestValueObject()
        ]);
    }

    public function testTwoValueObjectsWithSameArgumentNames()
    {
        $arguments = $this->argumentResolver->resolveArguments([new TestClass(), 'twoValueObjectsMethod'], [
            'bar' => new TestValueObject('bar'),
            'foo' => new TestValueObject('foo')
        ]);

        $this->assertEquals(2, count($arguments));
        $this->assertTrue($arguments[0] instanceof TestValueObject);
        $this->assertEquals($arguments[0]->getValue(), 'foo');
        $this->assertTrue($arguments[1] instanceof TestValueObject);
        $this->assertEquals($arguments[1]->getValue(), 'bar');
    }

    /**
     * @expectedException \SRIO\ArgumentResolver\Exception\ResolutionException
     */
    public function testRequiredScalarNotResolved()
    {
        $this->argumentResolver->resolveArguments([new TestClass(), 'anOptionalScalarArgument'], [
            'bar' => 'bar'
        ]);
    }

    public function testOptionalScalarNotResolved()
    {
        $arguments = $this->argumentResolver->resolveArguments([new TestClass(), 'anOptionalScalarArgument'], [
            'foo' => 'bar'
        ]);

        $this->assertEquals(['bar'], $arguments);
    }

    public function testCallableIsCalled()
    {
        $testClassProphet = $this->prophesize('SRIO\ArgumentResolver\Tests\Fixtures\TestClass');
        $testClassProphet->namedParametersMethod(1, 2)->shouldBeCalled();
        $testClass = $testClassProphet->reveal();

        $this->argumentResolver->call([$testClass, 'namedParametersMethod'], [
            'bar' => 2,
            'foo' => 1
        ]);
    }
}
