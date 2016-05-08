---
currentSection: v1
currentItem: FactoryList
pageflow_next_url: HasOverridden.html
pageflow_next_text: HasOverridden check
---

# Overview

## Introduction

This is a list of all of the classes available in the `GanbaroDigital\DIContainers\V1\FactoryList` namespace.

This component provides a factory-driven DI container, plus related method classes.

This container was originally created as a way of encapsulating `Library2` inside `Library1`.

* `Library1` can tell `Library2` to use exceptions defined by `Library1`.
* Anyone who is using `Library1` will not see (or have to catch) exceptions defined by `Library2`.

## Available Checks

Check | Description
------|------------
[`HasOverridden`](HasOverridden.html) | have we overridden all instance builders provided by another library?

Click on any of the classes for full details.

## Available Containers

Entity | Description
-------|------------
[`FactoryListContainer`](FactoryListContainer.html) | factory-driven DI container

Click on any of the classes for full details.

## Available Filters

Filter | Description
-------|------------
[`FilterNonOverriddenFactoryList`](FilterNonOverriddenFactoryList.html) | given our list and another library's list, produce a list of their instance builders that we have not overridden

Click on any of the classes for full details.
