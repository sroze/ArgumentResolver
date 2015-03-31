<?php
namespace SRIO\ArgumentResolver\Tests;

use SRIO\ArgumentResolver\ArgumentResolver;
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
        $this->argumentResolver = new ArgumentResolver();
    }

    public function testGetArguments()
    {
        $object = new TestValueObject();
        $testClass = new TestClass();

        $arguments = $this->argumentResolver->resolveArguments([$testClass, 'valueObjectMethod'], [
            'foo' => 'bar',
            'value' => $object
        ]);

        $this->assertEquals($arguments, [$object]);
    }

    public function testScalarAndTypeObjectArguments()
    {
        $object = new TestValueObject();
        $testClass = new TestClass();

        $arguments = $this->argumentResolver->resolveArguments([$testClass, 'valueObjectAndScalarMethod'], [
            'foo' => 'bar',
            'value' => $object
        ]);

        $this->assertEquals($arguments, [$object, 'bar']);
    }

    /**
     * @expectedException \SRIO\ArgumentResolver\Exception\ResolutionException
     */
    public function testArrayWithArgument()
    {
        $object = new TestValueObject();
        $testClass = new TestClass();

        $arguments = $this->argumentResolver->resolveArguments([$testClass, 'valueArrayMethod'], [
            'foo' => 'bar',
            'value' => $object
        ]);
    }
}
