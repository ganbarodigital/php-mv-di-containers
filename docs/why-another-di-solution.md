---
currentSection: overview
currentItem: background
pageflow_prev_url: index.html
pageflow_prev_text: Introduction
pageflow_next_url: license.html
pageflow_next_text: License
---

# Why Another DI Solution?

There are plenty of existing dependency-injection solutions already available for PHP. So why create another one?

It all started with an overhaul of the way the Ganbaro Digital libraries create and throw exceptions.

## Exceptional Needs

### Catch Ladders

The Ganbaro Digital PHP libraries use exceptions for all error handling. Many of the libraries are built on top of other libraries. This led to situations like this:

```php
try {
    $obj->doSomething();
}
catch (Library1\Exceptions\UnsupportedType $e) {
    // ...
}
catch (Library2\Exceptions\UnsupportedType $e) {
    // ...
}
```

In the example above, any of the libraries can throw an exception that means the same thing (`UnsupportedType`). But each library (rightly) has its own class to do so. There are several problems with this situation:

* If you're calling functionality provided by `Library1`, you shouldn't need to know that it relies on `Library2` - or any other libraries for that matter. What if a future version of `Library1` switches to a different set of dependencies? All of a sudden, your code (the person using `Library1`) is potentially broken. This is a clear breach of the encapsulation principle.
* Your code ends up being littered with duplicated `catch` blocks. In PHP, each `catch` block can only catch a single type of exception at a time. If you need to catch and handle `UnsupportedType` with dedicated code, you're forced to have multiple `catch` blocks, each containing duplicated code.
* The larger the `catch` ladder, the harder it is to reason about the code's behaviour (exactly in the same way that `if / else` ladders and the pyramid of doom does). Ideally we want to reduce or even eliminate `catch` ladders wherever we can.

The encapsulation principle is also breached if both `Library1` and `Library2` agree to throw the same `UnsupportedType` exception from a common base library.

What does a completely-encapsulated exception strategy look like?

### Ideal Encapsulation

What we want to be able to code is this:

```php
try {
    $obj->doSomething();
}
catch (Library1\Exceptions\UnsupportedType $e) {
    // ...
}
```

and make it `Library1`s job to only ever return `Library1\Exceptions\UnsupportedType` regardless of what kind of exception `Library1`s dependencies choose to throw.

That can be done inside `Library1` like this:

```php
try {
    $library2obj->doSomething();
}
catch (Library2\Exceptions\UnsupportedType $e) {
    throw new Library1\Exceptions\UnsupportedType(...);
}
```

but this switches one set of problems for another:

* `Library1` ends up being littered with `try / catch` blocks that are used only for translating exceptions from one type to another. It's a lot of extra code to write - and to test.
* The exception stack trace is no longer complete and accurate. It now starts from where `Library1` translated the original exception. That might not be enough to diagnose and resolve the issue.

What we really want is to tell `Library2` et al which exceptions we want them to throw. That sounds like a classic dependency-injection approach.

Only, as we'll see, it really isn't.

### Traditional DI - Pass In Ready-Built Objects

The traditional dependency-injection solution is to pass ready-built objects in as parameters. This might be into the constructor or into setter methods. (We're not going to argue about the merits of either approach here).

```php
class MyClass
{
    public function __construct(..., UnsupportedType $e1)
    {
        // ...
        $this->unsupportedType = $e1;
    }

    public function doSomething()
    {
        throw $this->unsupportedType;
    }
}
```

That can never work for PHP exceptions.

* [PHP exceptions](http://php.net/manual/en/class.exception.php) are value objects. Once created, you can't edit them. So in our `doSomething()` method, there's no way for us to set the exception's message to provide essential information about why the exception has been thrown.
* An exception's stack trace is set when an exception is created. With the traditional DI pattern, the exception is created somewhere away from where it is thrown. As a result, when you dump the exception's stack trace to see where the error occurred, the stack trace doesn't contain that information.
* It doesn't scale. One of our guiding principles is that software should be easy to reason about. Part of that means creating explicit exceptions for each kind of error wherever possible, and avoiding generic exceptions for the most part. If we had to pass each individual exception into a constructor, the constructor would grow to a vast and unwieldy size.

On top of that, many PHP dependency-injection containers are actually _service locators_: they return the same instance of an object time and time again. That doesn't work for exceptions, because we want __new__ instances each and every time.

### Bending The Rules - Pass In The Exception Class

In earlier iterations of the Ganbaro Digital libraries, we tried a compromise. Instead of passing in ready-built exceptions (which clearly was a total non-starter), we decided to pass in class names instead.

```php
class MyClass
{
    public function __construct(..., $e1)
    {
        // ...
        $this->unsupportedType = $e1;
    }

    public function doSomething()
    {
        $eType = $this->unsupportedType;
        throw new $eType(...);
    }
}
```

And that worked, to a point.

* Just like attempting to pass in pre-build objects, it doesn't scale. We ended up with constructors that were too large.
* It allowed us to change the exception class being thrown (goal #1 accomplished), but it meant that our replacement exceptions had to have the same constructor as `Library2`'s exceptions. When we decided to use static factory methods on our exceptions, that became a bit of a problem. Yes, we could hard-code the static factory method everywhere, but that felt like something we should not have to do.

### Breaking The Rules - Pass In The List Of Exception Factories

What we really wanted to do was to pass in a list of static factory methods - a list of PHP callables - and have our classes use these callables to create the exception right where they're going to be thrown from.

```php
class MyClass
{
    public function __construct(..., $exceptionsList)
    {
        $this->exceptionsList = $exceptionsList;
    }

    public function doSomething()
    {
        throw $this->exceptionsList['UnsupportedType'](...);
    }
}
```

Never mind bending the rules, this breaks the rules of existing dependency-injection practice and dependency-injection containers.

* A goldern rule of DI containers is that you never pass the DI container itself into objects. This is considered one of the top anti-patterns for DI. And for _service locators_, we completely agree. But we consider our list of exceptions a special case. We're not passing in a list of services; we're passing in a list of types (and their constructors) to use.

  Passing in the list of exceptions is ultimately more readable and more manageable than passing in each exception class as a separate parameter. It's also way more resilient to change in the future.

  This is the kind of thing that people get their soapbox out for!

* DI containers call factories for you, and return a ready-built object for you to use. We want a DI container that returns the factory for us to call from our own code.

Ignoring the lynch-mobs for now, passing in an array of exception factories turned out to be an incomplete solution.

* What happens if the exception alias isn't in the list? PHP will return a `null` (and older versions generate a legacy `E_NOTICE` first). `null` isn't a valid `callable`. Older versions of PHP will generate a fatal error (which crashes your code!). From PHP 7.0 onwards, this generates a `Throwable` exception. It's going to be a few years before we can write code that only runs on PHP 7.x, and legacy fatal errors are just downright nasty news for any production system.
* Similiary, what happens if the exception alias is in the list, but it hasn't been assigned a valid PHP `callable`?

One of our guiding principles is to make it as easy as possible to detect programming errors before the code is shipped to production.  A PHP array is lightning-quick, but there's no way to attach these kinds of robustness checks to one.  We have to create some kind of container to do so.

We looked around at the existing DI containers, but couldn't find one that met our needs.

* We don't want a service locator (for this specific problem).
* We don't want the DI container to call the factory for us.
* We don't want autowiring at all.
* We want to enforce the required properties of our list.
* We want to be able to perform additional robustness checks (e.g. making sure `Library1` has provided exceptions for everything `Library2` defines) in unit tests.

We decided that it made sense to build our own.
