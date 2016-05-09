---
currentSection: v1
currentItem: FactoryList
pageflow_prev_url: index.html
pageflow_prev_text: FactoryList namespace
---

# FactoryListContainer

<div class="callout warning">
Not yet in a tagged release
</div>

## Description

`FactoryListContainer` is a container. It holds a list of factory methods and functions, indexed by name.

* _factory name_ - the lookup key for the factory method or function
* _factory_ - the factory method or function (can be any PHP `callable`, including `__invoke`able objects)

`FactoryListContainer` is a specialist dependency-injection container. It doesn't build dependencies for you. When you use it, it returns factory methods for you to call from your own code.

## Public Interface

`FactoryListContainer` has the following public interface:

```php
// `FactoryListContainer` lives in this namespace
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

// our base classes and interfaces
use ArrayAccess;
use GanbaroDigital\DIContainers\V1\Interfaces\FactoryList;
use GanbaroDigital\MissingBits\Entities\WriteProtectedEntity;

class FactoryListContainer
  implements FactoryList, WriteProtectedEntity
{
    /**
     * create a managed list of factories
     *
     * the array has the following format:
     * - 'key' is the name or alias for your factor
     * - 'value' is a callable that will return something
     *
     * examples of keys include:
     *
     * - 'NotAFactoryList::newFromVar'
     * - 'NotAFactory::newFromNonCallable'
     *
     * the only requirements on 'value' are that
     * - it is a callable
     *
     * @param array $factories
     *        the list of factories to define
     * @param FactoryList $exceptions
     *        the list of exceptions to throw
     */
    public function __construct($factories, FactoryList $exceptions = null);

    /**
     * return the full list of aliases and builders as a real PHP array
     *
     * @return array
     * @inheritedFrom FactoryList
     */
    public function getList();

    /**
     * can we edit this entity?
     *
     * @return boolean
     *         FALSE if we can edit this container
     *         TRUE otherwise
     * @inheritedFrom WriteProtectedEntity
     */
    public function isReadOnly();

    /**
     * can we edit this container?
     *
     * @return boolean
     *         TRUE if we can edit this container
     *         FALSE otherwise
     * @inheritedFrom WriteProtectedEntity
     */
    public function isReadWrite();

    /**
     * disable editing this container
     *
     * @return void
     * @inheritedFrom WriteProtectedEntity
     */
    public function setReadOnly();

    /**
     * enable editing this container
     *
     * @return void
     * @inheritedFrom WriteProtectedEntity
     */
    public function setReadWrite();
}
```

## How To Use

### Defining Dependencies

`FactoryListContainer` is a factory-driven DI container. You give it a list of factories (the factory) and their names:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer([
    'UnsupportedType::newFromVar' => [ UnsupportedType::class, 'newFromVar' ],
    'NoSuchClass::newFromClassName' => [ NoSuchClass::class, 'newFromClassName' ],
]);
```

The array key is a string, and can be anything you like. We recommend that you use a _classname::methodName_ pair. That makes it easier for other libraries (or apps) to replace your dependencies with their own should they need to.

<div class="callout info" markdown="1">
`FactoryListContainer` doesn't parse the array key. It's only used as a lookup key.

There are no plans to add any kind of magic in the future. We believe that magic makes it harder for someone else to reason about your code - especially if they've never used the same libraries and frameworks before.
</div>

The array value has to be a valid PHP `callable`. This can be any one of:

* class name and static public method name pair
* object and non-static public method name pair
* global function name
* an object that has an `__invoke()` method
* an anonymous function

Internally, we use PHP's `is_callable()` function to check the array value. We throw an exception if `is_callable()` rejects any of the array values.

The array value can accept any input parameters that it wants.

<div class="callout info" markdown="1">
The array value - the factory - should create and return a new object every time it is called.
</div>

### Creating Dependencies

Treat `FactoryListContainer` as a PHP array that contains functions you can call:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer([
    'UnsupportedType::newFromVar' => [ UnsupportedType::class, 'newFromVar' ],
    'NoSuchClass::newFromClassName' => [ NoSuchClass::class, 'newFromClassName' ],
]);

// use the DI container to create our exception
$exception = $deps['NoSuchClass::newFromClassName'](...);
```

In this example, we retrieve the factory from the container, and then call it ourselves. We are responsible for passing in any parameters that our chosen factory expects. As far as PHP is concerned, we're calling a PHP `callable` directly from our code.

<div class="callout warning" markdown="1">
Note that `FactoryListContainer` itself does not create your dependency. It returns the `callable` factory for you to call.
</div>

<div class="callout warning" markdown="1">
Also be aware that `FactoryListContainer` is not a _service locator_. Every time you use one of the factories in the `FactoryListContainer` to create a dependency, you are creating a new instance of your dependency.
</div>

### Adding Or Replacing Dependencies

`FactoryListContainer` is an entity. It's read-only by default. You can put it into read-write mode and then treat it as a PHP array:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer([
    'UnsupportedType::newFromVar' => [ UnsupportedType::class, 'newFromVar' ],
]);

// replace the dependency
$deps->setReadWrite();
$deps['UnsupportedType::newFromVar'] = function() { throw new RuntimeException; };
```

<div class="callout danger" markdown="1">
In the code that ships to Production, treat `FactoryListContainer` as a read-only container.

Only use this feature in your unit tests.
</div>

### Removing Dependencies

`FactoryListContainer` is an entity. It's read-only by default. You can put it into read-write mode and then treat it as a PHP array:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer([
    'UnsupportedType::newFromVar' => [ UnsupportedType::class, 'newFromVar' ],
]);

// remove the dependency
$deps->setReadWrite();
unset($deps['UnsupportedType::newFromVar']);
```

<div class="callout danger" markdown="1">
In the code that ships to Production, treat `FactoryListContainer` as a read-only container.

Only use this feature in your unit tests.
</div>

## Exceptions Thrown

### When You Don't Provide A List Of Factories

If you do something like this:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

// cannot use NULL as a list of factories
$deps = new FactoryListContainer(null);

// cannot use booleans as a list of factories
$deps = new FactoryListContainer(true);
$deps = new FactoryListContainer(false);

// cannot use callables as a list of factories
$deps = new FactoryListContainer(function(){});

// cannot use doubles as a list of factories
$deps = new FactoryListContainer(3.1415927);

// cannot use integers as a list of factories
$deps = new FactoryListContainer(100);

// cannot use objects as a list of factories
$deps = new FactoryListContainer(new \stdClass);

// cannot use resources as a list of factories
$deps = new FactoryListContainer(STDIN);

// cannot use strings as a list of factories
$deps = new FactoryListContainer("BUILD ALL THE THINGS");
```

then `FactoryListContainer` will throw a `NotAListOfFactories` exception.

### When You Provide Something That Isn't A Factory

If you do something like this:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer([
    // oops - a class name on its own isn't a PHP callable
    'UnsupportedType::newFromVar' => UnsupportedType::class,
]);
```

then `FactoryListContainer` will throw a `NotAFactory` exception.

### When The Factory Cannot Be Found

If you do something like this:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer([
    'UnsupportedType::newFromVar' => [ UnsupportedType::class, 'newFromVar' ],
]);

// oops ... 'NoSuchClass::newFromClassName' isn't registered!
$exception = $deps['NoSuchClass::newFromClassName'](...);
```

then `FactoryListContainer` will throw a `NoSuchFactory` exception.

### Attempting To Edit A Read-Only Container

If you do something like this:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer();

// oops - container is in read-only mode
$deps['UnsupportedType::newFromVar'] = [ UnsupportedType::class, 'newFromVar' ];
```

then `FactoryListContainer` will throw a `ContainerIsReadOnly` exception.

You can avoid this exception by making the container read-write first:

```php
use GanbaroDigital\DIContainers\V1\FactoryList\Containers\FactoryListContainer;

$deps = new FactoryListContainer();

$deps->setReadWrite();
$deps['UnsupportedType::newFromVar'] = [ UnsupportedType::class, 'newFromVar' ];
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

## See Also

* [`FactoryList`](../Interfaces/FactoryList.html) - interface for a factory-driven dependency-injection container
* [`NoSuchFactory`](../Exceptions/NoSuchFactory.html) - exception thrown when we're asked for a factory, and have no factory registered under that name
* [`NotAFactory`](../Exceptions/NotAFactory.html) - exception thrown when we're given something that we cannot use as an factory
* [`NotAListOfFactories`](../Exceptions/NotAListOfFactories.html) - exception thrown when we're given something we cannot use to build this container
* [`Why Another DI Solution?`](../../why-another-di-solution.html) - the story of how this container came to be
