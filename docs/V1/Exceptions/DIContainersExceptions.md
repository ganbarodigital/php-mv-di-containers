---
currentSection: v1
currentItem: exceptions
pageflow_prev_url: NotAnInstanceBuilderList.html
pageflow_prev_text: NotAnInstanceBuilderList class
---

# DIContainersExceptions

<div class="callout warning">
Not yet in a tagged release
</div>

## Description

`DIContainersExceptions` is an [`InstanceBuildersList`](../Interfaces/InstanceBuildersList.html). It provides a list of all exceptions that the _Dependency-Injection Containers Library_ can build, along with a factory method for each exception.

## Public Interface

`DIContainersExceptions` has the following public interface:

```php
// DIContainersExceptions lives in this namespace
namespace GanbaroDigital\DIContainers\V1\Exceptions;

// our base classes and interfaces
use GanbaroDigital\DIContainers\V1\InstanceBuilders\Entities\InstanceBuilders;
use GanbaroDigital\DIContainers\V1\Interfaces\InstanceBuildersList;

class DIContainersExceptions extends InstanceBuilders
{
    public function __construct();

    /**
     * return the full list of aliases and builders as a real PHP array
     *
     * @return array
     * @inheritedFrom InstanceBuildersList
     */
    public function getList();
}
```

## How To Use

### Construction

Here's how to build a new instance of `DIContainersExceptions`:

```php
use GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions;

$exceptions = new DIContainersExceptions;
```

### Overriding The Exceptions

The whole point of this class is that your library can override the exceptions thrown by this library.

```php
use GanbaroDigital\DIContainers\V1\InstanceBuilders\Entities\InstanceBuilders;

class MyLibraryExceptions extends InstanceBuilders
{
    public function __construct()
    {
        // my library's exceptions
        $ourExceptions = [
            'alias' => [ MyExceptionClass::class, 'newFromXXX' ],
            // make sure this list overrides every exception included in
            // DIContainersExceptions
        ];

        // inject our list into this entity
        // if there are any problems, my library's exceptions will be
        // used, not the ones in the DIContainers library
        parent::__construct($ourExceptions, $this);
    }
}
```

## Class Contract

Here is the contract for this class:

    GanbaroDigital\DIContainers\V1\Exceptions\DIContainersExceptions
     [x] Can instantiate
     [x] Can get instance builder for no builder for instance alias
     [x] Can get instance builder for not an instance builder
     [x] Can get instance builder for not an instance builder list

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
