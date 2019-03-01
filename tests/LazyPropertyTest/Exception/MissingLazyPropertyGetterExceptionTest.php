<?php

declare(strict_types=1);

namespace LazyPropertyTest\Exception;

use LazyProperty\Exception\MissingLazyPropertyGetterException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Tests for {@see \LazyProperty\Exception\MissingLazyPropertyGetterException}
 *
 * @covers \LazyProperty\Exception\MissingLazyPropertyGetterException
 */
class MissingLazyPropertyGetterExceptionTest extends TestCase
{
    public function testInvalidContext() : void
    {
        $this->assertStringMatchesFormat(
            'The getter "getFoo" for lazy property "foo" is not defined in "stdClass#%s"',
            MissingLazyPropertyGetterException::fromGetter(new stdClass(), 'getFoo', 'foo')->getMessage()
        );
    }
}
