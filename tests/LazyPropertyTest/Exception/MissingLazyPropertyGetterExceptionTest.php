<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace LazyPropertyTest\Exception;

use LazyProperty\Exception\InvalidAccessException;
use LazyProperty\Exception\InvalidLazyProperty;
use LazyProperty\Exception\MissingLazyPropertyGetterException;
use LazyProperty\Util\AccessScopeChecker;
use LazyPropertyTestAsset\InheritedPropertiesClass;
use LazyPropertyTestAsset\ParentClass;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Tests for {@see \LazyProperty\Exception\MissingLazyPropertyGetterException}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 *
 * @covers \LazyProperty\Exception\MissingLazyPropertyGetterException
 */
class MissingLazyPropertyGetterExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testInvalidContext()
    {
        $this->assertStringMatchesFormat(
            'The getter "getfoo" for lazy property "foo" is not defined in "stdClass#%s"',
            MissingLazyPropertyGetterException::fromGetter(new stdClass(), 'getfoo', 'foo')->getMessage()
        );
    }
}
