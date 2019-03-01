<?php

declare(strict_types=1);

namespace LazyProperty\Exception;

use LogicException;
use function get_class;
use function spl_object_hash;
use function sprintf;

/**
 * Exception for missing lazy properties
 */
class InvalidLazyProperty extends LogicException implements ExceptionInterface
{
    public static function nonExistingLazyProperty(object $instance, string $property) : self
    {
        return new self(sprintf(
            'The requested lazy property "%s" is not defined in "%s#%s"',
            $property,
            get_class($instance),
            spl_object_hash($instance)
        ));
    }
}
