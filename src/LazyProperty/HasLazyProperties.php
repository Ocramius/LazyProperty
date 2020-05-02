<?php

declare(strict_types=1);

namespace LazyProperty;

use LazyProperty\Exception\InvalidLazyProperty;
use LazyProperty\Exception\MissingLazyPropertyGetter;
use LazyProperty\Util\AccessScopeChecker;
use ReflectionException;
use function debug_backtrace;
use function method_exists;
use const DEBUG_BACKTRACE_PROVIDE_OBJECT;

/**
 * Trait providing lazy initialization of object properties
 */
trait HasLazyProperties
{
    /** @var bool[] indexed by property name */
    private array $lazyPropertyAccessors = [];

    /**
     * Initializes lazy properties so that first access causes their initialization via a getter
     *
     * @param string[] $lazyPropertyNames
     *
     * @throws MissingLazyPropertyGetter
     */
    private function initLazyProperties(array $lazyPropertyNames, bool $checkLazyGetters = true) : void
    {
        foreach ($lazyPropertyNames as $lazyProperty) {
            if ($checkLazyGetters && ! method_exists($this, 'get' . $lazyProperty)) {
                throw MissingLazyPropertyGetter::fromGetter($this, 'get' . $lazyProperty, $lazyProperty);
            }

            $this->lazyPropertyAccessors[$lazyProperty] = false;

            if (isset($this->$lazyProperty)) {
                continue;
            }

            unset($this->$lazyProperty);
        }
    }

    /**
     * Magic getter - initializes and gets a property
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public function & __get(string $name)
    {
        if (! isset($this->lazyPropertyAccessors[$name])) {
            throw InvalidLazyProperty::nonExistingLazyProperty($this, $name);
        }

        $caller = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[1];

        // small optimization to avoid initializing reflection out of context
        if (! isset($caller['object']) || $caller['object'] !== $this) {
            AccessScopeChecker::checkCallerScope($caller, $this, $name);
        }

        $this->$name = null;
        $this->$name = $this->{'get' . $name}();

        return $this->$name;
    }
}
