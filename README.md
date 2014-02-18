# Lazy Map

This small library aims at providing a very simple and efficient loading of lazy properties

[![Build Status](https://travis-ci.org/Ocramius/LazyProperty.png?branch=master)](https://travis-ci.org/Ocramius/LazyProperty)
[![Coverage Status](https://coveralls.io/repos/Ocramius/LazyProperty/badge.png?branch=master)](https://coveralls.io/r/Ocramius/LazyProperty)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/Ocramius/LazyProperty/badges/quality-score.png?s=7b727f09ad4fe0ff092312a85eec8999e2e3e120)](https://scrutinizer-ci.com/g/Ocramius/LazyProperty/)
[![Total Downloads](https://poser.pugx.org/ocramius/lazy-map/downloads.png)](https://packagist.org/packages/ocramius/lazy-map)
[![Latest Stable Version](https://poser.pugx.org/ocramius/lazy-map/v/stable.png)](https://packagist.org/packages/ocramius/lazy-map)
[![Latest Unstable Version](https://poser.pugx.org/ocramius/lazy-map/v/unstable.png)](https://packagist.org/packages/ocramius/lazy-map)
[![Dependency Status](https://www.versioneye.com/php/ocramius:lazy-map/dev-master/badge.png)](https://www.versioneye.com/php/ocramius:lazy-map/dev-master)

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
php composer.phar require ocramius/lazy-property:1.0.*
```

## Use case

In many cases where lazy-initialization of private/protected properties is necessary,
many people write classes that look like following:

```php
class SomeService
{
    protected $dependency;

    public function doWork()
    {
        $this->getDependency()->delegateWork();
    }

    protected function getDependency()
    {
        return $this->dependency ?: $this->dependency = get_dependency_somehow();
    }
}
```

This is problematic because implementors and people subclassing `SomeService` will eventually
write:

```php
class SomethingElse extends SomeService
{
    public function doOtherWork()
    {
        $this->dependency->doMoreWork();
    }
}
```

This can work only if `SomeService#getDependency()` was called at least once upfront (which
may well be under certain circumstances), and therefore is a cause of bugs/headaches/suicides/etc.

In order to avoid this problem, the implementor of `SomeService` that is also exposing
its protected `$dependency` property may just use `LazyProperty` to fix the problem:


```php
class SomeService
{
    use LazyMap\LazyPropertiesTrait;

    protected $dependency;

    public function __construct()
    {
        $this->initLazyProperties(['dependency']);
    }

    public function doWork()
    {
        $this->getDependency()->delegateWork();
    }

    protected function getDependency()
    {
        return $this->dependency ?: $this->dependency = get_dependency_somehow();
    }
}
```

With this, any access to `SomeService#$dependency` will cause a call to
`SomeService#getDependency()` if the property was not already initialized.


```php
class SomethingElse extends SomeService
{
    public function doOtherWork()
    {
        // always works
        $this->dependency->doMoreWork();
    }
}
```