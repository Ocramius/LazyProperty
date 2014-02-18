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

namespace LazyPropertyTest;

use LazyProperty\Util\AccessScopeChecker;
use LazyPropertyTestAsset\InheritedPropertiesClass;
use LazyPropertyTestAsset\ParentClass;
use PHPUnit_Framework_TestCase;

/**
 * Tests for {@see \LazyProperty\Util\AccessScopeChecker}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 *
 * @covers \LazyProperty\Util\AccessScopeChecker
 */
class AccessScopeCheckerTest extends PHPUnit_Framework_TestCase
{
    private $foo;

    public function testAllowsAccessFromSameInstance()
    {
        $this->assertNull(AccessScopeChecker::checkCallerScope(['object' => $this], $this, 'foo'));
    }

    public function testAllowsAccessFromSubClass()
    {
        $this->assertNull(AccessScopeChecker::checkCallerScope(
            ['object' => new InheritedPropertiesClass()],
            new ParentClass(),
            'protected1'
        ));
    }

    public function testAllowsAccessFromSameClass()
    {
        $this->assertNull(AccessScopeChecker::checkCallerScope(
            ['object' => new ParentClass()],
            new ParentClass(),
            'private1'
        ));
    }

    public function testAllowsAccessFromReflectionProperty()
    {
        $this->assertNull(AccessScopeChecker::checkCallerScope(
            ['object' => new \ReflectionProperty(new ParentClass(), 'private1')],
            new ParentClass(),
            'private1'
        ));
    }

    public function testDisallowsAccessFromGlobalOrFunctionScope()
    {
        $this->setExpectedException('LazyProperty\\Exception\\InvalidAccessException');
        $this->assertNull(AccessScopeChecker::checkCallerScope(
            [],
            new ParentClass(),
            'private1'
        ));
    }

    public function testDisallowsPrivateAccessFromDifferentScope()
    {
        $this->setExpectedException('LazyProperty\\Exception\\InvalidAccessException');
        $this->assertNull(AccessScopeChecker::checkCallerScope(
            ['object' => $this],
            new ParentClass(),
            'private1'
        ));
    }

    public function testDisallowsProtectedAccessFromDifferentScope()
    {
        $this->setExpectedException('LazyProperty\\Exception\\InvalidAccessException');
        $this->assertNull(AccessScopeChecker::checkCallerScope(
            ['object' => $this],
            new ParentClass(),
            'private1'
        ));
    }
}
