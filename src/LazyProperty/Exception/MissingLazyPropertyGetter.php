<?php

declare(strict_types=1);

namespace LazyProperty\Exception;

use InvalidArgumentException;

use function spl_object_hash;
use function sprintf;

/**
 * Exception for missing property getters
 */
class MissingLazyPropertyGetter extends InvalidArgumentException implements Exception
{
    public static function fromGetter(object $instance, string $getter, string $property): self
    {
        return new self(sprintf(
            'The getter "%s" for lazy property "%s" is not defined in "%s#%s"',
            $getter,
            $property,
            $instance::class,
            spl_object_hash($instance),
        ));
    }
}
