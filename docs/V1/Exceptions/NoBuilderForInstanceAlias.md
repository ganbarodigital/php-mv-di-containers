---
currentSection: v1
currentItem: exceptions
pageflow_prev_url: index.html
pageflow_prev_text: Exceptions List
pageflow_next_url: NotAnInstanceBudiler.html
pageflow_next_text: NotAnInstanceBuidler class
---

# NoBuilderForInstanceAlias

<div class="callout warning" markdown="1">
Not yet in a tagged release
</div>

## Description

`NoBuilderForInstanceAlias` is an exception. It is thrown when we do not have a builder for a given instance alias.

## Public Interface

`NoBuilderForInstanceAlias` has the following public interface:

```php
// NoBuilderForInstanceAlias lives in this namespace
namespace GanbaroDigital\DIContainers\V1\Exceptions;

// our base classes and interfaces
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersException;
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

// return type(s) for our methods
use GanbaroDigital\HttpStatus\StatusValues\RuntimeError\UnexpectedErrorStatus;

class NoBuilderForInstanceAlias
  extends ParameterisedException
  implements DIContainersException, HttpRuntimeErrorException
{
    // adds 'getHttpStatus()' that returns a HTTP 500 status value object
    use UnexpectedErrorStatusProvider;

    /**
     * create a new exception
     *
     * @param  mixed $instanceAlias
     *         the name of the instance that we could not find a builder for
     * @param  array|null $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return NoBuilderForInstanceAlias
     *         an fully-built exception for you to throw
     */
    public static function newFromInstanceAlias($instanceAlias, $callerFilter = null);

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

Call `NoBuilderForInstanceAlias::newFromInstanceAlias()` to create a new exception that you can throw:

```php
use GanbaroDigital\DIContainers\V1\Exceptions\NoBuilderForInstanceAlias;

throw NoBuilderForInstanceAlias::newFromInstanceAlias("trout");
```

### Catching The Exception

`NoBuilderForInstanceAlias` implements a rich set of classes and interfaces. You can use any of these to catch this exception.

```php
// example 1: we catch only NoBuilderForInstanceAlias exceptions
use GanbaroDigital\DIContainers\V1\Exceptions\NoBuilderForInstanceAlias

try {
    throw NoBuilderForInstanceAlias::newFromInstanceAlias("trout");
}
catch(NoBuilderForInstanceAlias $e) {
    // ...
}
```

```php
// example 2: catch all exceptions thrown by the DIContainers Library
use GanbaroDigital\DIContainers\V1\Exceptions\NoBuilderForInstanceAlias
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersException;

try {
    throw NoBuilderForInstanceAlias::newFromInstanceAlias("trout");
}
catch(DIContainersException $e) {
    // ...
}
```

```php
// example 3: catch all exceptions where something went wrong that
// should never happen
use GanbaroDigital\DIContainers\V1\Exceptions\NoBuilderForInstanceAlias
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

try {
    throw NoBuilderForInstanceAlias::newFromInstanceAlias("trout");
}
catch(HttpRuntimeErrorException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 4: catch all exceptions that map onto a HTTP status
use GanbaroDigital\DIContainers\V1\Exceptions\NoBuilderForInstanceAlias
use GanbaroDigital\HttpStatus\Interfaces\HttpException;

try {
    throw NoBuilderForInstanceAlias::newFromInstanceAlias("trout");
}
catch(HttpException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 5: catch all runtime exceptions
use GanbaroDigital\DIContainers\V1\Exceptions\NoBuilderForInstanceAlias
use RuntimeException;

try {
    throw NoBuilderForInstanceAlias::newFromInstanceAlias("trout");
}
catch(RuntimeException $e) {
    // ...
}
```

## Class Contract

Here is the contract for this class:

    GanbaroDigital\DIContainers\V1\Exceptions\NoBuilderForInstanceAlias
     [x] Can instantiate
     [x] Is d i containers exception
     [x] Is parameterised exception
     [x] Is runtime exception
     [x] Is http runtime error exception
     [x] Maps to unexpected error status
     [x] Can build from instance alias
     [x] Can pass caller filter into build from instance alias
     [x] Build from instance alias will use default caller filter if no filter provided

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

* [`InstanceBuilders`](../InstanceBuilders/InstanceBuilders.html) - factory-driven dependency injection container
