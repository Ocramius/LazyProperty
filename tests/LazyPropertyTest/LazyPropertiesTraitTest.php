<?php

declare(strict_types=1);

namespace LazyPropertyTest;

use LazyProperty\Exception\InvalidAccess;
use LazyProperty\Exception\InvalidLazyProperty;
use LazyProperty\Exception\MissingLazyPropertyGetter;
use LazyPropertyTestAsset\AClass;
use LazyPropertyTestAsset\BClass;
use LazyPropertyTestAsset\InheritedPropertiesClass;
use LazyPropertyTestAsset\LazyGetterClass;
use LazyPropertyTestAsset\MixedPropertiesClass;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

/**
 * Tests for {@see \LazyProperty\LazyPropertiesTrait}
 *
 * @covers \LazyProperty\LazyPropertiesTrait
 */
class LazyPropertiesTraitTest extends TestCase
{
    protected MixedPropertiesClass $instance;

    public function setUp(): void
    {
        $this->instance = new MixedPropertiesClass();
    }

    public function testMixedLazyPropertiesAreLazilyInitialized(): void
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

    public function testAllMixedLazyPropertiesAreLazilyInitialized(): void
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

    public function testMixedLazyPropertiesAreLazilyInitializedWithProtectedAccess(): void
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

    public function testMixedInheritedLazyPropertiesAreLazilyInitialized(): void
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

    public function testThrowsExceptionOnMissingLazyGetter(): void
    {
        $instance = new MixedPropertiesClass();

        $this->expectException(MissingLazyPropertyGetter::class);
        $instance->initProperties(['nonExisting']);
    }

    public function testDoesNotRaiseWarningsForNonExistingProperties(): void
    {
        $instance = new LazyGetterClass();

        $instance->initProperties(['property']);

        $this->assertSame('property', $instance->getProperty());
    }

    public function testDeniesAccessToNonExistingLazyProperties(): void
    {
        $this->expectException(InvalidLazyProperty::class);
        (new LazyGetterClass())->nonExisting;
    }

    public function testDeniesAccessToProtectedLazyProperties(): void
    {
        $instance = new MixedPropertiesClass();

        $instance->initProperties(['protected1']);
        $this->expectException(InvalidAccess::class);
        $instance->protected1;
    }

    public function testDeniesAccessToPrivateLazyProperties(): void
    {
        $instance = new MixedPropertiesClass();

        $instance->initProperties(['private1']);
        $this->expectException(InvalidAccess::class);
        $instance->private1;
    }

    public function testGetMultiInheritanceProperties(): void
    {
        $instanceA = new AClass();
        $instanceB = new BClass();

        $instanceA->initALazyProperties();
        $instanceB->initBLazyProperties();

        $this->assertSame(AClass::class, $this->getProperty($instanceA, 'private'));
        $this->assertSame(BClass::class, $this->getProperty($instanceB, 'private'));
    }

    public function testDoesNotReInitializeDefinedProperties(): void
    {
        $instance = new MixedPropertiesClass();

        $instance->public1 = 'defined';

        $instance->initProperties(['public1']);

        $this->assertSame('defined', $instance->public1);
    }

    /**
     * @return mixed
     *
     * @throws ReflectionException
     */
    private function getProperty(object $instance, string $propertyName)
    {
        $reflectionClass = new ReflectionClass($instance);

        while ($reflectionClass && ! $reflectionClass->hasProperty($propertyName)) {
            $reflectionClass = $reflectionClass->getParentClass();
        }

        if (! $reflectionClass) {
            throw new UnexpectedValueException('Property "' . $propertyName . '" does not exist');
        }

        $reflectionProperty = $reflectionClass->getProperty($propertyName);

        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($instance);
    }
}
