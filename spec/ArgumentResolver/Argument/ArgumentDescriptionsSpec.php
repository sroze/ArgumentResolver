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

    function it_returns_the_description_at_the_given_position()
    {
        $this->add(new ArgumentDescription('foo', 0, ArgumentDescription::TYPE_SCALAR, true));
        $description = new ArgumentDescription('bar', 1, ArgumentDescription::TYPE_SCALAR, true);
        $this->add($description);

        $this->getByPosition(1)->shouldReturn($description);
    }

    function it_returns_the_number_of_descriptions()
    {
        $this->count()->shouldBe(0);
        $this->add(new ArgumentDescription('foo', 0, ArgumentDescription::TYPE_SCALAR, true));
        $this->count()->shouldBe(1);
    }
}
