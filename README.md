# Argument Resolver

[![Build Status](https://travis-ci.org/sroze/ArgumentResolver.svg?branch=master)](https://travis-ci.org/sroze/ArgumentResolver)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/88e4a3f8-bc5e-44e2-84e4-5f8c514ad62f/mini.png)](https://insight.sensiolabs.com/projects/88e4a3f8-bc5e-44e2-84e4-5f8c514ad62f)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sroze/ArgumentResolver/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sroze/ArgumentResolver/?branch=master)

This lightweight library helps to automatically call a callable (function, method or closure) with a list of
available arguments. The developer of the callable can then use type hinting and/or specific variable names
to chose which arguments (s)he wants.

## Installation

The suggested installation method is via composer:
```
composer require sroze/argument-resolver
```

## Resolving arguments

The argument resolver can be created easily using the `ArgumentResolverFactory` class:
```php
use ArgumentResolver\ArgumentResolverFactory;

$argumentResolver = ArgumentResolverFactory::create();
```

The `resolveArguments` method returns an ordered array of resolved arguments for the given callable. The method's arguments
are:

1. The [callable](http://php.net/manual/en/language.types.callable.php)
2. The available arguments, as an array

Here's an example of how it can be used to have arguments of some closures based on a set of available arguments:
```php
$closures = [
    function(MyClass $object) {
        return $object instanceof MyClass;
    },
    function($bar, array $list) {
        return count($list);
    }
];

foreach ($closures as $callable) {
    $arguments = $argumentResolver->resolveArguments($callable, [
        'classObject' => new MyClass(),
        'bar' => 'foo',
        'list' => ['an', 'array']
    ]);
    
    // ...
}
```

More than argument identification with type hinting, you can also use names:
```php
class Foo
{
    public function method($foo, $bar)
    {
    }
}

$argumentResolver->resolveArguments([new Foo(), 'method'], [
    'bar' => 1,
    'foo' => 2,
    'baz' => 3
]);

// Which returns: [2, 1]
```

To prevent possible conflicts, the library follow priorities and constraints described in the [Rules](#rules) chapter.

## The callable runner

Because when you've resolved the needed arguments of a given callable it's often to call it, the library comes with a
`CallableRunner` class that will do everything for you:

```php
$runner = new CallableRunner($argumentResolver);
$runner->run($callable, $availableArguments);
```

## Instantiate an object

Sometimes, you would like to instantiate an object with a set of arguments. Here's the `Instanciator` usage:
```php
$instantiator = new Instantiator($argumentResolver);
$instantiator->instantiate(YourClassName::class, $availableArguments);
```

## Rules

### Priorities

These are the resolution priorities for the arguments:

1. *Strict matching:* Same name and same type
2. *Type matching*
3. *Name matching*

## Constraints

The following constraints applies:

- If there's multiple arguments of the same type, then strict matching apply for this type
- Required arguments have to be resolved

