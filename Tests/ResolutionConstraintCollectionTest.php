<?php
namespace ArgumentResolver\Tests;

use ArgumentResolver\ResolutionConstraint;
use ArgumentResolver\ResolutionConstraintCollection;

class ResolutionConstraintCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstraintsWithDifferentArguments()
    {
        $collection = new ResolutionConstraintCollection([
            new ResolutionConstraint(ResolutionConstraint::STRICT_MATCHING, [
                'type' => 'DateTime'
            ])
        ]);

        $this->assertFalse($collection->hasConstraint(ResolutionConstraint::STRICT_MATCHING, [
            'type' => 'FooBar'
        ]));
    }

    public function testConstraintsWithUnknownArgument()
    {
        $collection = new ResolutionConstraintCollection([
            new ResolutionConstraint(ResolutionConstraint::STRICT_MATCHING, [
                'type' => 'DateTime'
            ])
        ]);

        $this->assertFalse($collection->hasConstraint(ResolutionConstraint::STRICT_MATCHING, [
            'foo' => 'FooBar'
        ]));
    }
}
