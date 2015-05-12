<?php
namespace SRIO\ArgumentResolver\Tests;

use ArgumentResolver\ArgumentDescription;
use ArgumentResolver\ArgumentDescriptor;

class ArgumentDescriptorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArgumentDescriptor
     */
    private $argumentDescriptor;

    public function setUp()
    {
        $this->argumentDescriptor = new ArgumentDescriptor();
    }

    public function testDescribeClosures()
    {
        $descriptions = $this->argumentDescriptor->getDescriptions(function($foo, array $bar) {});
        $this->assertEquals('foo', $descriptions[0]->getName());
        $this->assertEquals('bar', $descriptions[1]->getName());
        $this->assertEquals(ArgumentDescription::TYPE_ARRAY, $descriptions[1]->getType());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionIsNonCallable()
    {
        $this->argumentDescriptor->getDescriptions('non_callable');
    }
}
