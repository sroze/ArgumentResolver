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
        $this->add(new Resolution(1, 2, 3, 4));
    }

    function it_supports_addind_collection_of_resolutions()
    {
        $this->addCollection([
            new Resolution(1, 2, 3, 4),
        ]);
    }

    function it_is_Traversable()
    {
        $this->shouldHaveType(\Traversable::class);
    }

    function it_sorts_the_resolutions_by_priority()
    {
        $this->add(new Resolution(1, 'one', 2, 1));
        $this->add(new Resolution(1, 'two', 2, 2));

        $this->sortByPriority()->toArray()->shouldBeLike([
            new Resolution(1, 'two', 2, 2),
            new Resolution(1, 'one', 2, 1),
        ]);
    }

    function it_returns_the_argument_values_as_array()
    {
        $this->add(new Resolution(0, 'one', 'foo', 1));
        $this->add(new Resolution(2, 'two', 'bar', 2));
        $this->add(new Resolution(1, 'three', 2, 2));

        $this->toArgumentsArray()->shouldReturn([
            'foo',
            2,
            'bar',
        ]);
    }

    function it_returns_the_missing_resolution_keys()
    {
        $this->add(new Resolution(0, 'one', 'foo', 1));
        $this->add(new Resolution(2, 'two', 'bar', 2));
        $this->add(new Resolution(4, 'three', 'bar', 2));

        $this->getMissingResolutionPositions()->shouldReturn([1, 3]);
    }

    function it_returns_the_missing_resolution_keys_including_the_missing_parameter()
    {
        $this->add(new Resolution(0, 'one', 'foo', 1));
        $this->add(new Resolution(2, 'two', 'bar', 2));

        $this->getMissingResolutionPositions(4)->shouldReturn([1, 3]);
    }

    function it_returns_the_missing_resolution_keys_including_the_missing_parameters()
    {
        $this->add(new Resolution(0, 'one', 'foo', 1));
        $this->add(new Resolution(2, 'two', 'bar', 2));

        $this->getMissingResolutionPositions(5)->shouldReturn([1, 3, 4]);
    }

    function it_returns_a_missing_resolution_with_no_resolutions()
    {
        $this->getMissingResolutionPositions(1)->shouldBe([0]);
    }

    function it_returns_no_missing_resolutions_if_no_expected_parameters()
    {
        $this->getMissingResolutionPositions(0)->shouldBe([]);
    }
}
