<?php

namespace ArgumentResolver;

use ArgumentResolver\Argument\ArgumentDescription;
use ArgumentResolver\Argument\ArgumentDescriptions;
use ArgumentResolver\Argument\ArgumentDescriptor;
use ArgumentResolver\Exception\ArgumentResolutionException;
use ArgumentResolver\Exception\ResolutionException;
use ArgumentResolver\Resolution\ConstraintResolver;
use ArgumentResolver\Resolution\Resolution;
use ArgumentResolver\Resolution\ResolutionConstraint;
use ArgumentResolver\Resolution\ResolutionConstraintCollection;
use ArgumentResolver\Resolution\Resolutions;

class ArgumentResolver
{
    /**
     * @var ArgumentDescriptor
     */
    private $argumentDescriptor;

    /**
     * @var ConstraintResolver
     */
    private $constraintResolver;

    /**
     * @param ArgumentDescriptor $argumentDescriptor
     * @param ConstraintResolver $constraintResolver
     */
    public function __construct(ArgumentDescriptor $argumentDescriptor, ConstraintResolver $constraintResolver)
    {
        $this->argumentDescriptor = $argumentDescriptor;
        $this->constraintResolver = $constraintResolver;
    }

    /**
     * Resolve the arguments needed by the given callable and the order of these
     * arguments.
     *
     * It returns an array with the value of arguments in the right order.
     *
     * @param mixed $callable
     * @param array $availableArguments
     *
     * @return array
     */
    public function resolveArguments($callable, array $availableArguments = [])
    {
        $descriptions = $this->argumentDescriptor->getDescriptions($callable)->sortByPosition();
        $constraints = $this->constraintResolver->resolveConstraints($descriptions);

        $resolutions = new Resolutions();
        foreach ($descriptions as $description) {
            $resolutions->addCollection(
                $this->getArgumentResolutions($constraints, $description, $availableArguments)
            );
        }

        $this->addMissingResolutions($resolutions, $descriptions);

        $arguments = $resolutions->sortByPriority()->toArgumentsArray();

        $this->validateAgainstExtraArguments($availableArguments, $resolutions);

        return $arguments;
    }

    /**
     * @param ResolutionConstraintCollection $constraints
     * @param ArgumentDescription            $description
     * @param array                          $availableArguments
     *
     * @return Resolution[]
     */
    private function getArgumentResolutions(ResolutionConstraintCollection $constraints, ArgumentDescription $description, array $availableArguments)
    {
        $resolutions = [];

        foreach ($availableArguments as $argumentName => $argumentValue) {
            $priority = $this->getArgumentPriority($constraints, $description, $argumentName, $argumentValue);

            if ($priority > 0) {
                $resolutions[] = new Resolution($description->getPosition(), $argumentName, $argumentValue, $priority);
            }
        }

        return $resolutions;
    }

    /**
     * @param ResolutionConstraintCollection $constraints
     * @param ArgumentDescription            $description
     * @param string                         $argumentName
     * @param mixed                          $argumentValue
     *
     * @return int
     */
    private function getArgumentPriority(ResolutionConstraintCollection $constraints, ArgumentDescription $description, $argumentName, $argumentValue)
    {
        $priority = 0;
        if ($description->getName() === $argumentName) {
            $priority++;
        }

        if ($description->isScalar()) {
            return $priority;
        }

        if ($description->getType() === $this->argumentDescriptor->getValueType($argumentValue)) {
            $priority += 2;
        } elseif ($constraints->hasConstraint(ResolutionConstraint::STRICT_MATCHING, [
            'type' => $description->getType(),
        ])) {
            throw new ResolutionException(sprintf(
                'Strict matching for type "%s" can\'t be resolved',
                $description->getType()
            ));
        }

        return $priority;
    }

    /**
     * @param Resolutions          $resolutions
     * @param ArgumentDescriptions $descriptions
     *
     * @throws ResolutionException
     */
    private function addMissingResolutions(Resolutions $resolutions, ArgumentDescriptions $descriptions)
    {
        $missingResolutionPositions = $resolutions->getMissingResolutionPositions($descriptions->count());

        foreach ($missingResolutionPositions as $position) {
            $description = $descriptions->getByPosition($position);
            if ($description->isRequired()) {
                throw new ArgumentResolutionException(
                    sprintf(
                        'Argument at position %d is required and wasn\'t resolved',
                        $description->getPosition()
                    ),
                    $description
                );
            }

            $resolutions->add(new Resolution($description->getPosition(), $description->getName(), $description->getDefaultValue(), 0));
        }
    }

    private function validateAgainstExtraArguments(array $availableArguments, Resolutions $resolutions)
    {
        $unrecognisedArguments = array_diff(array_keys($availableArguments), $resolutions->argumentNames());
        if (count($unrecognisedArguments) > 1) {
            throw new RuntimeException(sprintf(
                'The following arguments are not known: "%s", known arguments: "%s"',
                implode('", "', $unrecognisedArguments), implode('", "', $resolutions->argumentNames())
            ));
        }
    }
}
