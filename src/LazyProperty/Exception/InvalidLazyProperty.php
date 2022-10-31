<?php

declare(strict_types=1);

namespace LazyProperty\Exception;

use LogicException;

use function spl_object_hash;
use function sprintf;

/**
 * Exception for missing lazy properties
 */
class InvalidLazyProperty extends LogicException implements Exception
{
    public static function nonExistingLazyProperty(object $instance, string $property): self
    {
        return new self(sprintf(
            'The requested lazy property "%s" is not defined in "%s#%s"',
            $property,
            $instance::class,
            spl_object_hash($instance),
        ));
    }
}
