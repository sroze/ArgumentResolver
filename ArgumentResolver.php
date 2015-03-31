<?php
namespace SRIO\ArgumentResolver;

use SRIO\ArgumentResolver\Exception\ResolutionException;

final class ArgumentResolver
{
    /**
     * Call the given callable thing and inject the right values according
     * to the available values.
     *
     * @param mixed $callable
     * @param array $availableArguments
     * @return mixed
     */
    public function call($callable, array $availableArguments = [])
    {
        return call_user_func_array($callable, $this->resolveArguments($availableArguments));
    }

    /**
     * Resolve the arguments needed by the given callable and the order of these
     * arguments.
     *
     * It returns an array with the value of arguments in the right order.
     *
     * @param mixed $callable
     * @param array $availableArguments
     * @return array
     */
    public function resolveArguments($callable, array $availableArguments = [])
    {
        $descriptor = new ArgumentDescriptor();
        $descriptions = $descriptor->getDescriptions($callable);
        $this->sortDescriptions($descriptions);

        $resolutions = [];
        foreach ($descriptions as $description) {
            foreach ($availableArguments as $argumentName => $argumentValue) {
                $priority = 0;

                if ($description->getType() === $descriptor->getValueType($argumentValue)) {
                    $priority += 2;
                }
                if ($description->getName() === $argumentName) {
                    $priority++;
                }

                if ($priority === 0) {
                    continue;
                }

                $resolutions[] = [
                    'priority' => $priority,
                    'position' => $description->getPosition(),
                    'value' => $argumentValue
                ];
            }
        }

        usort($resolutions, function($left, $right) {
            return $left['position'] < $right['position'] ? 1 : -1;
        });

        $arguments = [];
        foreach ($resolutions as $resolution) {
            if (array_key_exists($resolution['position'], $arguments)) {
                continue;
            }

            $arguments[$resolution['position']] = $resolution['value'];
        }

        if (count($arguments) !== count($descriptions)) {
            throw new ResolutionException(sprintf(
                'Resolved %d arguments there\'s %d required arguments',
                count($arguments),
                count($descriptions)
            ));
        }

        return $arguments;
    }

    /**
     * @param ArgumentDescription[] $descriptions
     */
    private function sortDescriptions(array $descriptions)
    {
        usort($descriptions, function($left, $right) {
            return $left->getPosition() > $right->getPosition() ? 1 : -1;
        });
    }
}
