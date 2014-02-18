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

use LazyPropertyTestAsset\InheritedPropertiesClass;
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
    /**
     * @var MixedPropertiesClass
     */
    protected $instance;

    /**
     * @var \Closure[]
     */
    private $inspectors;

    public function setUp()
    {
        $this->instance = new MixedPropertiesClass();
    }

    public function testMixedLazyPropertiesAreLazilyInitialized()
    {
        $instance = new MixedPropertiesClass();

        $this->assertNull($this->getProperty($instance, 'public1'));
        $this->assertNull($this->getProperty($instance, 'public2'));
        $this->assertNull($this->getProperty($instance, 'protected1'));
        $this->assertNull($this->getProperty($instance, 'protected2'));
        $this->assertNull($this->getProperty($instance, 'private1'));
        $this->assertNull($this->getProperty($instance, 'private2'));

        $instance->initProperties(['public1', 'protected1', 'private1']);

        $this->assertSame('public1', $this->getProperty($instance, 'public1'));
        $this->assertNull($this->getProperty($instance, 'public2'));
        $this->assertSame('protected1', $this->getProperty($instance, 'protected1'));
        $this->assertNull($this->getProperty($instance, 'protected2'));
        $this->assertSame('private1', $this->getProperty($instance, 'private1'));
        $this->assertNull($this->getProperty($instance, 'private2'));
    }

    public function testAllMixedLazyPropertiesAreLazilyInitialized()
    {
        $instance = new MixedPropertiesClass();

        $this->assertNull($this->getProperty($instance, 'public1'));
        $this->assertNull($this->getProperty($instance, 'public2'));
        $this->assertNull($this->getProperty($instance, 'protected1'));
        $this->assertNull($this->getProperty($instance, 'protected2'));
        $this->assertNull($this->getProperty($instance, 'private1'));
        $this->assertNull($this->getProperty($instance, 'private2'));

        $instance->initProperties(['public1', 'public2', 'protected1', 'protected2', 'private1', 'private2']);

        $this->assertSame('public1', $this->getProperty($instance, 'public1'));
        $this->assertSame('public2', $this->getProperty($instance, 'public2'));
        $this->assertSame('protected1', $this->getProperty($instance, 'protected1'));
        $this->assertSame('protected2', $this->getProperty($instance, 'protected2'));
        $this->assertSame('private1', $this->getProperty($instance, 'private1'));
        $this->assertSame('private2', $this->getProperty($instance, 'private2'));
    }

    public function testMixedLazyPropertiesAreLazilyInitializedWithProtectedAccess()
    {
        $instance = new MixedPropertiesClass();

        $this->assertNull($this->getProperty($instance, 'public1'));
        $this->assertNull($this->getProperty($instance, 'public2'));
        $this->assertNull($this->getProperty($instance, 'protected1'));
        $this->assertNull($this->getProperty($instance, 'protected2'));
        $this->assertNull($this->getProperty($instance, 'private1'));
        $this->assertNull($this->getProperty($instance, 'private2'));

        $instance->initProperties(['public1', 'protected1', 'private1']);

        $this->assertSame('public1', $instance->getProperty('public1'));
        $this->assertNull($instance->getProperty('public2'));
        $this->assertSame('protected1', $instance->getProperty('protected1'));
        $this->assertNull($instance->getProperty('protected2'));
        $this->assertSame('private1', $instance->getProperty('private1'));
        $this->assertNull($instance->getProperty('private2'));
    }

    public function testMixedInheritedLazyPropertiesAreLazilyInitialized()
    {
        $instance = new InheritedPropertiesClass();

        $this->assertNull($this->getProperty($instance, 'public1'));
        $this->assertNull($this->getProperty($instance, 'public2'));
        $this->assertNull($this->getProperty($instance, 'protected1'));
        $this->assertNull($this->getProperty($instance, 'protected2'));

        $instance->initProperties(['public1', 'protected1']);

        $this->assertSame('public1', $this->getProperty($instance, 'public1'));
        $this->assertNull($this->getProperty($instance, 'public2'));
        $this->assertSame('protected1', $this->getProperty($instance, 'protected1'));
        $this->assertNull($this->getProperty($instance, 'protected2'));
    }

    public function testThrowsExceptionOnMissingLazyGetter()
    {
        $instance = new MixedPropertiesClass();

        $this->setExpectedException('LazyProperty\\Exception\\MissingLazyPropertyGetterException');
        $instance->initProperties(['nonExisting']);
    }

    private function getProperty($instance, $propertyName)
    {
        $reflectionClass = new \ReflectionClass($instance);

        while ($reflectionClass && ! $reflectionClass->hasProperty($propertyName)) {
            $reflectionClass = $reflectionClass->getParentClass();
        }

        if (! $reflectionClass) {
            throw new \UnexpectedValueException('Property "' . $propertyName . '" does not exist');
        }

        $reflectionProperty = $reflectionClass->getProperty($propertyName);

        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($instance);
    }
}
