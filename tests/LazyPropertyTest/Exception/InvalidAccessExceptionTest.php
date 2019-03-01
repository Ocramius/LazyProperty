<?php

declare(strict_types=1);

namespace LazyPropertyTest\Exception;

use LazyProperty\Exception\InvalidAccessException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Tests for {@see \LazyProperty\Exception\InvalidAccessException}
 *
 * @covers \LazyProperty\Exception\InvalidAccessException
 */
class InvalidAccessExceptionTest extends TestCase
{
    public function testInvalidContext() : void
    {
        $this->assertStringMatchesFormat(
            'The requested lazy property "foo" of "stdClass#%s" is not accessible from the context of in "'
            . self::class . '"',
            InvalidAccessException::invalidContext($this, new stdClass(), 'foo')->getMessage()
        );
    }
}
