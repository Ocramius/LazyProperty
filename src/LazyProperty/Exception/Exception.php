<?php

declare(strict_types=1);

namespace LazyProperty\Exception;

use Throwable;

/**
 * Base Exception for LazyProperty
 * This marker interface can be used in a catch-block to catch everything this package throws at once if needed.
 * Instead of declaring the exact type of the exception, just use this interface.
 * All possible future exception types will implement this interface and hence be caught as well in existing catches.
 */
interface Exception extends Throwable
{
}
