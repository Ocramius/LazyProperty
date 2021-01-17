<?php

declare(strict_types=1);

namespace LazyPropertyTest\Exception;

use LazyProperty\Exception\InvalidAccess;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Tests for {@see \LazyProperty\Exception\InvalidAccess}
 *
 * @covers \LazyProperty\Exception\InvalidAccess
 */
class InvalidAccessTest extends TestCase
{
    public function testInvalidContext(): void
    {
        $this->assertStringMatchesFormat(
            'The requested lazy property "foo" of "stdClass#%s" is not accessible from the context of in "'
            . self::class . '"',
            InvalidAccess::invalidContext($this, new stdClass(), 'foo')->getMessage()
        );
    }
}
