<?php

namespace spec\ArgumentResolver\Argument;

use ArgumentResolver\Argument\ArgumentDescription;
use PhpSpec\ObjectBehavior;

class ArgumentDescriptionsSpec extends ObjectBehavior
{
    function it_can_be_initialized_without_resolutions()
    {
        $this->shouldHaveType('ArgumentResolver\Argument\ArgumentDescriptions');
    }

    function it_supports_adding_description()
    {
        $this->add(new ArgumentDescription('foo', 1, ArgumentDescription::TYPE_SCALAR, true));
    }

    function it_is_Traversable()
    {
        $this->shouldHaveType(\Traversable::class);
    }

    function it_sorts_the_descriptions_by_position()
    {
        $this->add(new ArgumentDescription('foo', 1, ArgumentDescription::TYPE_SCALAR, true));
        $this->add(new ArgumentDescription('bar', 0, ArgumentDescription::TYPE_SCALAR, true));

        $this->sortByPosition()->toArray()->shouldBeLike([
            new ArgumentDescription('bar', 0, ArgumentDescription::TYPE_SCALAR, true),
            new ArgumentDescription('foo', 1, ArgumentDescription::TYPE_SCALAR, true),
        ]);
    }
}
