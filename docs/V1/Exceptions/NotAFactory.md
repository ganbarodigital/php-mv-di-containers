---
currentSection: v1
currentItem: exceptions
pageflow_prev_url: NoSuchFactory.html
pageflow_prev_text: NoSuchFactory class
pageflow_next_url: NotAListOfFactories.html
pageflow_next_text: NotAListOfFactories class
---

# NotAFactory

<div class="callout info" markdown="1">
Since v1.2016050901
</div>

## Description

`NotAFactory` is an exception. It is thrown when we were expecting a factory (a PHP `callable`) but were given something else instead.

## Public Interface

`NotAFactory` has the following public interface:

```php
// NotAFactory lives in this namespace
namespace GanbaroDigital\DIContainers\V1\Exceptions;

// our base classes and interfaces
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersException;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

// return type(s) for our methods
use GanbaroDigital\HttpStatus\StatusValues\RuntimeError\UnexpectedErrorStatus;

class NotAFactory
  extends ParameterisedException
  implements DIContainersException, HttpRuntimeErrorException
{
    // adds 'getHttpStatus()' that returns a HTTP 500 status value object
    use UnexpectedErrorStatusProvider;

    /**
     * create a new exception
     *
     * @param  string $factoryName
     *         the name that the bad factory was given
     * @param  mixed $badFactory
     *         the non-callable that we were given
     * @param  int|null $typeFlags
     *         do we want any extra type information in the final exception message?
     * @param  array|null $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return NotAFactory
     *         an fully-built exception for you to throw
     */
     public static function newFromNonCallable(
        $factoryName,
        $badFactory,
        $typeFlags = null,
        $callerFilter = null
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

Call `NotAFactory::newFromNonCallable()` to create a new exception that you can throw:

```php
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NotAFactory;

throw NotAFactory::newFromNonCallable("trout");
```

### Catching The Exception

`NotAFactory` implements a rich set of classes and interfaces. You can use any of these to catch this exception.

```php
// example 1: we catch only NotAFactory exceptions
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory;

try {
    throw NotAFactory::newFromNonCallable("trout");
}
catch(NotAFactory $e) {
    // ...
}
```

```php
// example 2: catch all exceptions thrown by the Dependency-Injection Containers Library
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory;
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersException;

try {
    throw NotAFactory::newFromNonCallable("trout");
}
catch(DIContainersException $e) {
    // ...
}
```

```php
// example 3: catch all exceptions where something went wrong that
// should never happen
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

try {
    throw NotAFactory::newFromNonCallable("trout");
}
catch(HttpRuntimeErrorException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 4: catch all exceptions that map onto a HTTP status
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory;
use GanbaroDigital\HttpStatus\Interfaces\HttpException;

try {
    throw NotAFactory::newFromNonCallable("trout");
}
catch(HttpException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 5: catch all runtime exceptions
use GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory;
use RuntimeException;

try {
    throw NotAFactory::newFromNonCallable("trout");
}
catch(RuntimeException $e) {
    // ...
}
```

## Class Contract

Here is the contract for this class:

    GanbaroDigital\DIContainers\V1\Exceptions\NotAFactory
     [x] Can instantiate
     [x] Is d i containers exception
     [x] Is parameterised exception
     [x] Is runtime exception
     [x] Is http runtime error exception
     [x] Maps to unexpected error status
     [x] Can build from non callables
     [x] Exception message contains caller
     [x] Exception message contains exception alias
     [x] Exception message contains type of bad factory

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

## See Also

* [`FactoryList`](../Interfaces/FactoryList.html) - interface for a dependency-injection container for factories
* [`FactoryListContainer`](../FactoryList/FactoryListContainer.html) - dependency-injection container for factories
