<?php

namespace spec\ArgumentResolver\Resolution;

use PhpSpec\ObjectBehavior;

class ResolutionConstraintCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType('ArgumentResolver\Resolution\ResolutionConstraintCollection');
    }
}
