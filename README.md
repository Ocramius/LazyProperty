# Lazy Property

This small library aims at providing a very simple and efficient loading of lazy properties

Currently maintained by [Niklas Schöllhorn](https://github.com/nschoellhorn), taken over 3rd March, 2019

[![Latest Stable Version](https://poser.pugx.org/ocramius/lazy-property/v/stable.png)](https://packagist.org/packages/ocramius/lazy-property)
[![Latest Unstable Version](https://poser.pugx.org/ocramius/lazy-property/v/unstable.png)](https://packagist.org/packages/ocramius/lazy-property)

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
composer require ocramius/lazy-property
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

    protected MyDependency $dependency;

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
