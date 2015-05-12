<?php

namespace spec\ArgumentResolver\Resolution;

use PhpSpec\ObjectBehavior;

class ResolutionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(0, 'value', 1);
    }

    function it_exposes_value()
    {
        $this->value()->shouldReturn('value');
    }

    function it_exposes_position()
    {
        $this->position()->shouldReturn(0);
    }

    function it_exposes_priority()
    {
        $this->priority()->shouldReturn(1);
    }
}
