<?php

declare(strict_types=1);

namespace LazyProperty\Util;

use LazyProperty\Exception\InvalidAccess;
use ReflectionException;
use ReflectionProperty;

use function get_class;
use function is_subclass_of;

/**
 * Utility class to identify scope access violations
 */
class AccessScopeChecker
{
    /**
     * Utility used to verify that access to lazy properties is not happening from outside allowed scopes
     *
     * @internal
     *
     * @param object[] $caller the caller array as from the debug stack trace entry
     *
     * @throws ReflectionException
     *
     * @private
     */
    public static function checkCallerScope(array $caller, object $instance, string $property): void
    {
        $reflectionProperty = new ReflectionProperty($instance, $property);

        if (! $reflectionProperty->isPublic()) {
            if (! isset($caller['object'])) {
                throw InvalidAccess::invalidContext(null, $instance, $property);
            }

            $caller        = $caller['object'];
            $callerClass   = get_class($caller);
            $instanceClass = get_class($instance);

            if (
                $callerClass === $instanceClass
                || ($reflectionProperty->isProtected() && is_subclass_of($callerClass, $instanceClass))
                || $callerClass === ReflectionProperty::class
                || is_subclass_of($callerClass, ReflectionProperty::class)
            ) {
                return;
            }

            throw InvalidAccess::invalidContext($caller, $instance, $property);
        }
    }
}
