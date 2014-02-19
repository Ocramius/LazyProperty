# Lazy Property

This small library aims at providing a very simple and efficient loading of lazy properties

[![Build Status](https://travis-ci.org/Ocramius/LazyProperty.png?branch=master)](https://travis-ci.org/Ocramius/LazyProperty)
[![Code Coverage](https://scrutinizer-ci.com/g/Ocramius/LazyProperty/badges/coverage.png?s=e66a6e178d3bd3928562c2f87ded32321d00665e)](https://scrutinizer-ci.com/g/Ocramius/LazyProperty/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/Ocramius/LazyProperty/badges/quality-score.png?s=a9ba5b80c811ffd9fd552f160ca6a5ac7b959736)](https://scrutinizer-ci.com/g/Ocramius/LazyProperty/)
[![Total Downloads](https://poser.pugx.org/ocramius/lazy-property/downloads.png)](https://packagist.org/packages/ocramius/lazy-property)
[![Latest Stable Version](https://poser.pugx.org/ocramius/lazy-property/v/stable.png)](https://packagist.org/packages/ocramius/lazy-property)
[![Latest Unstable Version](https://poser.pugx.org/ocramius/lazy-property/v/unstable.png)](https://packagist.org/packages/ocramius/lazy-property)
[![Dependency Status](https://www.versioneye.com/php/ocramius:lazy-property/dev-master/badge.png)](https://www.versioneye.com/php/ocramius:lazy-property/dev-master)

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
its protected `$dependency` property may just use `LazyProperty\LazyPropertiesTrait` to fix the problem:


```php
class SomeService
{
    use \LazyProperty\LazyPropertiesTrait;

    protected $dependency;

    public function __construct()
    {
        $this->initLazyProperties(['dependency']);
    }

    public function doWork()
    {
        // look ma! no getter!
        $this->dependency->delegateWork();
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

Please note that a getter is *required* in order for the property to be lazy.

## Performance notes

Using `LazyProperty\LazyPropertiesTrait` allows to speed up applications where a massive
amount of getter calls is going on in private/protected scope.
There is some minor overhead in calling `SomeService#initLazyProperties()`, as well as in
the first property access, but it should be negligible.

