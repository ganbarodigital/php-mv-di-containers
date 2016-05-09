---
currentSection: overview
currentItem: changelog
pageflow_prev_url: multivariant.html
pageflow_prev_text: Multi-Variant
pageflow_next_url: contributing.html
pageflow_next_text: Contributing
---
# CHANGELOG

## develop branch

### New

* Added generic type for catching all exceptions that this library declares
  - added `DIContainersException`
* Added a DI container that contains builders for every exception in this library
  - added `DIContainersExceptions`
* Added DI container that returns invokable factories
  - added `FactoryList` interface
  - added `FactoryListContainer` DI container
  - added `ContainerIsReadOnly` exception
  - added `NoSuchFactory` exception
  - added `NotAFactory` exception
  - added `NotAListOfFactories` exception
