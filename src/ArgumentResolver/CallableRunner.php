<?php
namespace ArgumentResolver;

class CallableRunner
{
    /**
     * @var ArgumentResolver
     */
    private $argumentResolver;

    public function __construct(ArgumentResolver $argumentResolver)
    {
        $this->argumentResolver = $argumentResolver;
    }

    /**
     * Run the given callable with arguments from the available ones.
     *
     * @param callable $callable
     * @param array $availableArguments
     * @param bool $strict
     * @return mixed
     */
    public function run(callable $callable, array $availableArguments, $strict = false)
    {
        $arguments = $this->argumentResolver->resolveArguments($callable, $availableArguments, $strict);

        return call_user_func_array($callable, $arguments);
    }
}
