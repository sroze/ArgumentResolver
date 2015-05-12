<?php

namespace spec\ArgumentResolver\Resolution;

use ArgumentResolver\Resolution\Resolution;
use PhpSpec\ObjectBehavior;

class ResolutionsSpec extends ObjectBehavior
{
    function it_can_be_initialized_without_resolutions()
    {
        $this->shouldHaveType('ArgumentResolver\Resolution\Resolutions');
    }

    function it_supports_adding_resolution()
    {
        $this->add(new Resolution(1, 2, 3));
    }

    function it_is_Traversable()
    {
        $this->shouldHaveType(\Traversable::class);
    }

    function it_sorts_the_resolutions_by_priority()
    {
        $this->add(new Resolution(1, 2, 1));
        $this->add(new Resolution(1, 2, 2));

        $this->sortByPriority()->toArray()->shouldBeLike([
            new Resolution(1, 2, 2),
            new Resolution(1, 2, 1),
        ]);
    }

    function it_returns_the_argument_values_as_array()
    {
        $this->add(new Resolution(0, 'foo', 1));
        $this->add(new Resolution(2, 'bar', 2));
        $this->add(new Resolution(1, 2, 2));

        $this->toArgumentsArray()->shouldReturn(['foo', 2, 'bar']);
    }
}
