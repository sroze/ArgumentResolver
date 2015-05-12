<?php

namespace spec\ArgumentResolver\Resolution;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResolutionConstraintCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType('ArgumentResolver\Resolution\ResolutionConstraintCollection');
    }
}
