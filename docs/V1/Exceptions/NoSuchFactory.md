---
currentSection: v1
currentItem: exceptions
pageflow_prev_url: index.html
pageflow_prev_text: Exceptions List
pageflow_next_url: NotAFactory.html
pageflow_next_text: NotAFactory class
---

# NoSuchFactory

<div class="callout info" markdown="1">
Since v1.2016050901
</div>

## Description

`NoSuchFactory` is an exception. It is thrown when a container has no factory for a given name.

## Public Interface

`NoSuchFactory` has the following public interface:

```php
// NoSuchFactory lives in this namespace
namespace GanbaroDigital\DIContainers\V1\Exceptions;

// our base classes and interfaces
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersException;
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

// return type(s) for our methods
use GanbaroDigital\HttpStatus\StatusValues\RuntimeError\UnexpectedErrorStatus;

class NoSuchFactory
  extends ParameterisedException
  implements DIContainersException, HttpRuntimeErrorException
{
    // adds 'getHttpStatus()' that returns a HTTP 500 status value object
    use UnexpectedErrorStatusProvider;

    /**
     * create a new exception
     *
     * @param  mixed $factoryName
     *         the name of the factory that we do not know about
     * @param  array $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return NoSuchFactory
     *         an fully-built exception for you to throw
     */
    public static function newFromFactoryName(
        $factoryName,
        array $callerFilter = []
    );

    /**
     * what was the data that we used to create the printable message?
     *
     * @return array
     */
    public function getMessageData();

    /**
     * what was the format string we used to create the printable message?
     *
     * @return string
     */
    public function getMessageFormat();

    /**
     * which HTTP status code do we map onto?
     *
     * @return UnexpectedErrorStatus
     */
    public function getHttpStatus();
}
```

## How To Use

### Creating Exceptions To Throw

Call `NoSuchFactory::newFromFactoryName()` to create a new exception that you can throw:

```php
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;

throw NoSuchFactory::newFromFactoryName("trout");
```

### Catching The Exception

`NoSuchFactory` implements a rich set of classes and interfaces. You can use any of these to catch this exception.

```php
// example 1: we catch only NoSuchFactory exceptions
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;

try {
    throw NoSuchFactory::newFromFactoryName("trout");
}
catch(NoSuchFactory $e) {
    // ...
}
```

```php
// example 2: catch all exceptions thrown by the DIContainers Library
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersException;

try {
    throw NoSuchFactory::newFromFactoryName("trout");
}
catch(DIContainersException $e) {
    // ...
}
```

```php
// example 3: catch all exceptions where something went wrong that
// should never happen
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

try {
    throw NoSuchFactory::newFromFactoryName("trout");
}
catch(HttpRuntimeErrorException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 4: catch all exceptions that map onto a HTTP status
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;
use GanbaroDigital\HttpStatus\Interfaces\HttpException;

try {
    throw NoSuchFactory::newFromFactoryName("trout");
}
catch(HttpException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 5: catch all runtime exceptions
use GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory;
use RuntimeException;

try {
    throw NoSuchFactory::newFromFactoryName("trout");
}
catch(RuntimeException $e) {
    // ...
}
```

## Class Contract

Here is the contract for this class:

    GanbaroDigital\DIContainers\V1\Exceptions\NoSuchFactory
     [x] Can instantiate
     [x] Is d i containers exception
     [x] Is parameterised exception
     [x] Is runtime exception
     [x] Is http runtime error exception
     [x] Maps to unexpected error status
     [x] Can build from factory name
     [x] Can pass caller filter into new from factory name
     [x] New from factory name will use default caller filter if no filter provided

Class contracts are built from this class's unit tests.

<div class="callout success">
Future releases of this class will not break this contract.
</div>

<div class="callout info" markdown="1">
Future releases of this class may add to this contract. New additions may include:

* clarifying existing behaviour (e.g. stricter contract around input or return types)
* add new behaviours (e.g. extra class methods)
</div>

<div class="callout warning" markdown="1">
When you use this class, you can only rely on the behaviours documented by this contract.

If you:

* find other ways to use this class,
* or depend on behaviours that are not covered by a unit test,
* or depend on undocumented internal states of this class,

... your code may not work in the future.
</div>

## Notes

None at this time.

## Changelog

### v1.2016060601

* `$callerFilter` signature changed

  The `$callerFilter` parameter is now type-hinted as an array, and can no longer be `NULL`.

  If no `$callerFilter` parameter is provided, it now defaults to an empty list. It no longer defaults to `FilterCodeCaller::$DEFAULT_PARTIALS`; that list no longer exists.

  These changes were made to make this class compatible with the latest [`Exception Helpers Library`](http://ganbarodigital.github.io/php-mv-exception-helpers/).

## See Also

* [`FactoryList`](../Interfaces/FactoryList.html) - interface for a dependency-injection container for factories
* [`FactoryListContainer`](../FactoryList/FactoryListContainer.html) - dependency-injection container for factories
