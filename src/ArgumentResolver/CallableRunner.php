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
     * @param array    $availableArguments
     *
     * @return mixed
     */
    public function run(callable $callable, array $availableArguments)
    {
        $arguments = $this->argumentResolver->resolveArguments($callable, $availableArguments);

        return call_user_func_array($callable, $arguments);
    }
}
