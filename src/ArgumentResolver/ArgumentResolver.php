<?php
namespace ArgumentResolver;

use ArgumentResolver\Exception\ResolutionException;

final class ArgumentResolver
{
    /**
     * @var ArgumentDescriptor
     */
    private $argumentDescriptor;

    /**
     * @param ArgumentDescriptor $argumentDescriptor
     */
    public function __construct(ArgumentDescriptor $argumentDescriptor)
    {
        $this->argumentDescriptor = $argumentDescriptor;
    }

    /**
     * Resolve the arguments needed by the given callable and the order of these
     * arguments.
     *
     * It returns an array with the value of arguments in the right order.
     *
     * @param  mixed $callable
     * @param  array $availableArguments
     * @param bool $strict
     * @return array
     */
    public function resolveArguments($callable, array $availableArguments = [], $strict = false)
    {
        $descriptions = $this->argumentDescriptor->getDescriptions($callable);
        $this->sortDescriptions($descriptions);

        $resolutions = [];
        $constraints = $this->getResolutionConstraints($descriptions);
        foreach ($descriptions as $description) {
            foreach ($availableArguments as $argumentName => $argumentValue) {
                $priority = 0;

                if ($description->getName() === $argumentName) {
                    $priority++;
                }
                if (!$description->isScalar()) {
                    if ($description->getType() === $this->argumentDescriptor->getValueType($argumentValue)) {
                        $priority += 2;
                    } elseif ($constraints->hasConstraint(ResolutionConstraint::STRICT_MATCHING, [
                        'type' => $description->getType()
                    ])) {
                        throw new ResolutionException(sprintf(
                            'Strict matching for type "%s" can\'t be resolved',
                            $description->getType()
                        ));
                    }
                }

                if ($priority === 0) {
                    continue;
                }

                $resolutions[] = [
                    'priority' => $priority,
                    'position' => $description->getPosition(),
                    'value' => $argumentValue,
                ];
            }
        }

        usort($resolutions, function ($left, $right) {
            return $left['priority'] < $right['priority'] ? 1 : -1;
        });

        $arguments = [];
        foreach ($resolutions as $resolution) {
            if (array_key_exists($resolution['position'], $arguments)) {
                continue;
            }

            $arguments[$resolution['position']] = $resolution['value'];
        }

        foreach ($descriptions as $description) {
            if ($description->isRequired() && !array_key_exists($description->getPosition(), $arguments)) {
                throw new ResolutionException(sprintf(
                    'Argument at position %d is required and wasn\'t resolved',
                    $description->getPosition()
                ));
            }
        }

        ksort($arguments);

        return $arguments;
    }

    /**
     * @param ArgumentDescription[] $descriptions
     */
    private function sortDescriptions(array $descriptions)
    {
        usort($descriptions, function (ArgumentDescription $left, ArgumentDescription $right) {
            return $left->getPosition() > $right->getPosition() ? 1 : -1;
        });
    }

    /**
     * @param  ArgumentDescription[]          $descriptions
     * @return ResolutionConstraintCollection
     */
    private function getResolutionConstraints($descriptions)
    {
        $constraints = [];
        $types = [];
        foreach ($descriptions as $description) {
            if (in_array($description->getType(), $types)) {
                $constraints[] = new ResolutionConstraint(ResolutionConstraint::STRICT_MATCHING, [
                    'type' => $description->getType()
                ]);
            }

            $types[] = $description->getType();
        }

        return new ResolutionConstraintCollection($constraints);
    }
}
