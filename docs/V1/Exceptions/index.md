---
currentSection: v1
currentItem: exceptions
pageflow_next_url: ContainerIsReadOnly.html
pageflow_next_text: ContainerIsReadOnly class
---

# Exceptions

## Purpose

This is a list of all of the exceptions that this library can throw.

## Exceptions List

Class | Description
------|------------
[`ContainerIsReadOnly`](ContainerIsReadOnly.html) | thrown when you attempt to change the contents of a container, and you haven't put it into read-write mode first
[`NoSuchFactory`](NoSuchFactory.html) | thrown when there is no known factory for a given name
[`NotAFactory`](NotAFactory.html) | thrown when we have been given something that we cannot use as a factory
[`NotAListOfFactories`](NotAListOfFactories.html) | thrown when we have been given something that we cannot use as a list of factories

Click on the name of an exception for full details.

## Exceptions Container

[`DIContainersExceptions`](DIContainersExceptions.html) provides a full list of exception factories as an [`FactoryList`](../Interfaces/FactoryList.html).
