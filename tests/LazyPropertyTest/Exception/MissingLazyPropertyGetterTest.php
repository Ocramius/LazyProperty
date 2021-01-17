<?php

declare(strict_types=1);

namespace LazyPropertyTest\Exception;

use LazyProperty\Exception\MissingLazyPropertyGetter;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Tests for {@see \LazyProperty\Exception\MissingLazyPropertyGetter}
 *
 * @covers \LazyProperty\Exception\MissingLazyPropertyGetter
 */
class MissingLazyPropertyGetterTest extends TestCase
{
    public function testInvalidContext(): void
    {
        $this->assertStringMatchesFormat(
            'The getter "getFoo" for lazy property "foo" is not defined in "stdClass#%s"',
            MissingLazyPropertyGetter::fromGetter(new stdClass(), 'getFoo', 'foo')->getMessage()
        );
    }
}
