<?php
namespace ArgumentResolver\Tests\Fixtures;

class TestClass 
{
    public function valueObjectMethod(TestValueObject $value)
    {
    }

    public function objectAndScalarMethod(TestValueObject $object, $count)
    {
    }

    public function arrayMethod(array $collection)
    {
    }

    public function namedParametersMethod($foo, $bar)
    {
    }

    public function twoValueObjectsMethod(TestValueObject $foo, TestValueObject $bar)
    {
    }

    public function anOptionalScalarArgument($foo, $bar = null)
    {
    }
}
