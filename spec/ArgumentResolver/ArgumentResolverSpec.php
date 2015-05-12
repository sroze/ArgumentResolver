<?php

namespace spec\ArgumentResolver;

use ArgumentResolver\Argument\ArgumentDescription;
use ArgumentResolver\Argument\ArgumentDescriptor;
use ArgumentResolver\Exception\ResolutionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArgumentResolverSpec extends ObjectBehavior
{
    function it_can_resolve_an_argument_by_its_type(ArgumentDescriptor $argumentDescriptor)
    {
        $this->beConstructedWith($argumentDescriptor);

        $callable = function(){};
        $argumentDescriptor->getDescriptions($callable)->willReturn([
            new ArgumentDescription('value', 0, 'Test', true)
        ]);
        $argumentDescriptor->getValueType('scalar-bar')->willReturn(ArgumentDescription::TYPE_SCALAR);
        $argumentDescriptor->getValueType('object-Test')->willReturn('Test');

        $this->resolveArguments($callable, [
            'foo' => 'scalar-bar',
            'value' => 'object-Test'
        ])->shouldReturn(['object-Test']);
    }

    function it_can_resolve_arguments_by_its_name(ArgumentDescriptor $argumentDescriptor)
    {
        $this->beConstructedWith($argumentDescriptor);
        $callable = function(){};
        $argumentDescriptor->getDescriptions($callable)->willReturn([
            new ArgumentDescription('foo', 0, ArgumentDescription::TYPE_SCALAR, true),
            new ArgumentDescription('bar', 1, ArgumentDescription::TYPE_SCALAR, true),
        ]);
        $argumentDescriptor->getValueType(Argument::allOf(1, 2))->willReturn(ArgumentDescription::TYPE_SCALAR);

        $this->resolveArguments($callable, [
            'bar' => 1,
            'foo' => 2
        ])->shouldReturn([2, 1]);
    }

    function it_can_resolve_arguments_by_their_names_and_their_types(ArgumentDescriptor $argumentDescriptor)
    {
        $this->beConstructedWith($argumentDescriptor);
        $callable = function(){};
        $this->stubAnObjectAndScalarMethod($argumentDescriptor, $callable);

        $this->resolveArguments($callable, [
            'count' => 2,
            'value' => 'the-object'
        ])->shouldReturn(['the-object', 2]);
    }

    function it_throws_an_exception_if_scalar_argument_as_a_wrong_name(ArgumentDescriptor $argumentDescriptor)
    {
        $this->beConstructedWith($argumentDescriptor);
        $callable = function(){};
        $this->stubAnObjectAndScalarMethod($argumentDescriptor, $callable);

        $this->shouldThrow(ResolutionException::class)->during('resolveArguments', [$callable, [
            'foo' => 2,
            'value' => 'the-object'
        ]]);
    }

    function it_resolves_typed_arguments_by_their_name_if_two_arguments_of_the_same_type(ArgumentDescriptor $argumentDescriptor)
    {
        $this->beConstructedWith($argumentDescriptor);
        $callable = function(){};
        $argumentDescriptor->getDescriptions($callable)->willReturn([
            new ArgumentDescription('foo', 0, 'An\\Object', true),
            new ArgumentDescription('bar', 1, 'An\\Object', true),
        ]);
        $argumentDescriptor->getValueType(Argument::containingString('the-object'))->willReturn('An\\Object');

        $this->resolveArguments($callable, [
            'bar' => 'the-object-1',
            'foo' => 'the-object-2'
        ])->shouldBeLike([
            'the-object-2',
            'the-object-1'
        ]);
    }

    function it_resolve_even_if_optional_parameters_are_missing(ArgumentDescriptor $argumentDescriptor)
    {
        $this->beConstructedWith($argumentDescriptor);
        $callable = function(){};
        $argumentDescriptor->getDescriptions($callable)->willReturn([
            new ArgumentDescription('foo', 0, ArgumentDescription::TYPE_SCALAR, true),
            new ArgumentDescription('bar', 1, ArgumentDescription::TYPE_SCALAR, false),
        ]);
        $argumentDescriptor->getValueType('bar')->willReturn(ArgumentDescription::TYPE_SCALAR);

        $this->resolveArguments($callable, [
            'foo' => 'bar'
        ])->shouldReturn(['bar']);
    }

    function it_throw_an_exception_if_required_argument_is_missing(ArgumentDescriptor $argumentDescriptor)
    {
        $this->beConstructedWith($argumentDescriptor);
        $callable = function(){};
        $argumentDescriptor->getDescriptions($callable)->willReturn([
            new ArgumentDescription('foo', 0, ArgumentDescription::TYPE_SCALAR, true),
            new ArgumentDescription('bar', 1, ArgumentDescription::TYPE_SCALAR, false),
        ]);

        $argumentDescriptor->getValueType('bar')->willReturn(ArgumentDescription::TYPE_SCALAR);

        $this->shouldThrow(ResolutionException::class)->during('resolveArguments', [$callable, [
            'bar' => 'bar'
        ]]);
    }

    private function stubAnObjectAndScalarMethod(ArgumentDescriptor $argumentDescriptor, $callable)
    {
        $argumentDescriptor->getDescriptions($callable)->willReturn([
            new ArgumentDescription('object', 0, 'An\\Object', true),
            new ArgumentDescription('count', 1, ArgumentDescription::TYPE_SCALAR, true),
        ]);
        $argumentDescriptor->getValueType(2)->willReturn(ArgumentDescription::TYPE_SCALAR);
        $argumentDescriptor->getValueType('the-object')->willReturn('An\\Object');
    }
}
