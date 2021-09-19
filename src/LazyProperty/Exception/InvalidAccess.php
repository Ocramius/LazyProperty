<?php

declare(strict_types=1);

namespace LazyProperty\Exception;

use InvalidArgumentException;

use function gettype;
use function is_object;
use function spl_object_hash;
use function sprintf;

/**
 * Exception for invalid context access for lazy properties
 */
class InvalidAccess extends InvalidArgumentException implements Exception
{
    public static function invalidContext(mixed $caller, object $instance, string $property): self
    {
        return new self(sprintf(
            'The requested lazy property "%s" of "%s#%s" is not accessible from the context of in "%s"',
            $property,
            $instance::class,
            spl_object_hash($instance),
            is_object($caller) ? $caller::class : gettype($caller)
        ));
    }
}
