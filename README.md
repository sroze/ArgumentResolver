# Argument Resolver

This lightweight library helps to automatically call a callable (function, method or closure) with a list of
available arguments. The developer of the callable can then use type hinting and/or specific variable names
to chose which arguments (s)he wants.

## Installation

The suggested installation method is via composer:
```
composer require sroze/argument-resolver
```

## Usage

The argument resolver can be created easily using the `ArgumentResolverFactory` class:
```php
use SRIO\ArgumentResolver\ArgumentResolverFactory;

$argumentResolver = ArgumentResolverFactory::create();
```

You now have to endpoints on the `ArgumentResolver` class:
- `call`: simply call a given callable with a list of available arguments
- `resolveArguments`: returns an ordered array of resolved arguments for the given callback

Here's an example of an adaptive call on different closures.
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
    $result = $argumentResolver->call($callable, [
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

$argumentResolver->call([new Foo(), 'method'], [
    'bar' => 1,
    'foo' => 2,
    'baz' => 3
]);

// `method` has been called with the arguments (2, 1)
```

The `callable` argument can be anything that match the [PHP's `callable` type](http://php.net/manual/en/language.types.callable.php).

By the way, to prevent possible conflicts, the library follow priorities and constraints described in the [Rules](#rules)
chapter.

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

