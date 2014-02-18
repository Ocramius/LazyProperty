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

use LazyPropertyTestAsset\MixedPropertiesClass;
use PHPUnit_Framework_TestCase;

/**
 * Tests for {@see \LazyProperty\LazyPropertiesTrait}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 *
 * @covers \LazyProperty\LazyPropertiesTrait
 */
class LazyPropertiesTraitTest extends PHPUnit_Framework_TestCase
{
    protected $instance;

    public function setUp()
    {
        $this->instance = new MixedPropertiesClass();

    }

    public function testMixedLazyProperties()
    {
        $instance = new MixedPropertiesClass();

        $protected1Reflection = new \ReflectionProperty($instance, 'protected1');
        $protected2Reflection = new \ReflectionProperty($instance, 'protected2');
        $private1Reflection   = new \ReflectionProperty($instance, 'private1');
        $private2Reflection   = new \ReflectionProperty($instance, 'private2');

        $protected1Reflection->setAccessible(true);
        $protected2Reflection->setAccessible(true);
        $private1Reflection->setAccessible(true);
        $private2Reflection->setAccessible(true);

        $this->assertNull($instance->public1);
        $this->assertNull($instance->public2);
        $this->assertNull($protected1Reflection->getValue($instance));
        $this->assertNull($protected2Reflection->getValue($instance));
        $this->assertNull($private1Reflection->getValue($instance));
        $this->assertNull($private2Reflection->getValue($instance));

        $instance->initProperties(['public1', 'protected1', 'private1']);

        $this->assertSame('public1', $instance->public1);
        $this->assertNull($instance->public2);
        $this->assertSame('protected1', $protected1Reflection->getValue($instance));
        $this->assertNull($protected2Reflection->getValue($instance));
        $this->assertSame('private1', $private1Reflection->getValue($instance));
        $this->assertNull($private2Reflection->getValue($instance));
    }
}
