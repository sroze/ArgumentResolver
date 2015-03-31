<?php
namespace SRIO\ArgumentResolver\Tests\Fixtures;

class TestClass 
{
    public function valueObjectMethod(TestValueObject $value)
    {
    }

    public function valueObjectAndScalarMethod(TestValueObject $object, $count)
    {
    }

    public function valueArrayMethod(array $collection)
    {
    }
}
