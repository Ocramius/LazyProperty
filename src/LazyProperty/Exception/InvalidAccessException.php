<?php

declare(strict_types=1);

namespace LazyProperty\Exception;

use InvalidArgumentException;
use function get_class;
use function gettype;
use function is_object;
use function spl_object_hash;
use function sprintf;

/**
 * Exception for invalid context access for lazy properties
 */
class InvalidAccessException extends InvalidArgumentException implements ExceptionInterface
{
    /**
     * @param mixed $caller
     */
    public static function invalidContext($caller, object $instance, string $property) : self
    {
        return new self(sprintf(
            'The requested lazy property "%s" of "%s#%s" is not accessible from the context of in "%s"',
            $property,
            get_class($instance),
            spl_object_hash($instance),
            is_object($caller) ? get_class($caller) : gettype($caller)
        ));
    }
}
