<?php

declare(strict_types=1);

namespace LazyPropertyTest\Exception;

use LazyProperty\Exception\InvalidLazyProperty;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Tests for {@see \LazyProperty\Exception\InvalidLazyProperty}
 *
 * @covers \LazyProperty\Exception\InvalidLazyProperty
 */
class InvalidLazyPropertyTest extends TestCase
{
    public function testInvalidContext(): void
    {
        $this->assertStringMatchesFormat(
            'The requested lazy property "foo" is not defined in "stdClass#%s"',
            InvalidLazyProperty::nonExistingLazyProperty(new stdClass(), 'foo')->getMessage(),
        );
    }
}
