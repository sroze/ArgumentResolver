<?php
namespace ArgumentResolver\Resolution;

class ResolutionConstraintCollection 
{
    /**
     * @var ResolutionConstraint[]
     */
    private $constraints;

    /**
     * @param ResolutionConstraint[] $constraints
     */
    public function __construct(array $constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * @param int $type
     * @param array $withParameters
     * @return bool
     */
    public function hasConstraint($type, array $withParameters = [])
    {
        return count($this->getConstraints($type, $withParameters)) > 0;
    }

    /**
     * @param int $type
     * @param array $withParameters
     * @return ResolutionConstraint[]
     */
    public function getConstraints($type, array $withParameters = [])
    {
        $constraints = [];
        foreach ($this->constraints as $constraint) {
            if ($constraint->getType() === $type && $this->constraintHasParameters($constraint, $withParameters)) {
                $constraints[] = $constraint;
            }
        }

        return $constraints;
    }

    /**
     * @param ResolutionConstraint $constraint
     * @param array $parameters
     * @return bool
     */
    private function constraintHasParameters(ResolutionConstraint $constraint, array $parameters)
    {
        $constraintParameters = $constraint->getParameters();
        foreach ($parameters as $name => $value) {
            if (!array_key_exists($name, $constraintParameters)) {
                return false;
            } else if (null !== $value && $constraintParameters[$name] !== $value) {
                return false;
            }
        }

        return true;
    }
}
