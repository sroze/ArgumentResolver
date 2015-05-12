<?php

namespace spec\ArgumentResolver\Argument;

use ArgumentResolver\Argument\ArgumentDescription;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArgumentDescriptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('foo', 0, ArgumentDescription::TYPE_SCALAR, true);
        $this->shouldHaveType('ArgumentResolver\Argument\ArgumentDescription');
    }
}
