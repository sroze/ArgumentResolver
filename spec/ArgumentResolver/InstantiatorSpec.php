<?php

namespace spec\ArgumentResolver;

use ArgumentResolver\ArgumentResolver;
use ArgumentResolver\Resolution\Resolution;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InstantiatorSpec extends ObjectBehavior
{
    function it_instantiate_a_class_with_resolved_arguments(ArgumentResolver $argumentResolver)
    {
        $this->beConstructedWith($argumentResolver);
        $argumentResolver->resolveArguments(Argument::any(), Argument::any())->willReturn([1, 'foo', 'foo', 3]);
        $this->instantiate(Resolution::class, [])->shouldBeLike(new Resolution(1, 'foo', 'foo', 3));
    }
}
