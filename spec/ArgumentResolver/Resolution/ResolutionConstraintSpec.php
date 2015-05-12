<?php

namespace spec\ArgumentResolver\Resolution;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResolutionConstraintSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType('ArgumentResolver\Resolution\ResolutionConstraint');
    }
}
