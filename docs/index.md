---
currentSection: overview
currentItem: home
pageflow_next_url: why-another-di-solution.html
pageflow_next_text: Background
---

# Introduction

## What Is The Dependency-Injection Containers Library?

Ganbaro Digital's _Dependency-Injection Containers Library_ provides an easy-to-use collection of containers that you can use to add dependency-injection to your own apps and libraries.

## Goals

The _Dependency-Injection Containers Library_'s purpose is to provide dependency injection that's

* fast to run
* easy to reason about
* strongly-typed
* reveals coding errors before the code ships to Production

## Design Constraints

The library's design is guided by the following constraint(s):

* Generic containers - each type of container must be reusable
* A fundamental dependency - we're going to be using this to inject exception lists into many other libraries. Composer currently doesn't support circular dependencies. Therefore, this library has to depend on the bare minimum, otherwise Composer will refuse to install it.

## Questions?

This package was created by [Stuart Herbert](http://www.stuartherbert.com) for [Ganbaro Digital Ltd](http://ganbarodigital.com). Follow [@ganbarodigital](https://twitter.com/ganbarodigital) or [@stuherbert](https://twitter.com/stuherbert) for updates.
