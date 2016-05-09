---
currentSection: v1
currentItem: Interfaces
pageflow_prev_url: index.html
pageflow_prev_text: Interfaces
---

# FactoryList

<div class="callout info" markdown="1">
Since v1.2016050901
</div>

## Description

`FactoryList` is an interface. It is implemented by any DI container that holds and provides factory methods for you to call.

## Public Interface

`FactoryList` has the following public interface:

```php
// FactoryList lives in this namespace
namespace GanbaroDigital\DIContainers\V1\Interfaces;

// our base classes and interfaces
use ArrayAccess;

interface FactoryList extends ArrayAccess
{
    /**
     * return the full list of factories as a real PHP array
     *
     * @return array
     */
    public function getList();
}
```

## Notes

None at this time.

## See Also

* [`FactoryListContainer`](../FactoryList/FactoryListContainer.html) - an implementation of this interface
