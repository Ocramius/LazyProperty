<?php

declare(strict_types=1);

namespace LazyPropertyTest\Util;

use LazyProperty\Util\AccessScopeChecker;
use LazyPropertyTestAsset\InheritedPropertiesClass;
use LazyPropertyTestAsset\ParentClass;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/**
 * Tests for {@see \LazyProperty\Util\AccessScopeChecker}
 *
 * @covers \LazyProperty\Util\AccessScopeChecker
 */
class AccessScopeCheckerTest extends TestCase
{
    public function testAllowsAccessFromSameInstance() : void
    {
        AccessScopeChecker::checkCallerScope(['object' => $this], $this, 'backupGlobals');

        // Add to assertion count manually since we were successful when no exception was thrown and we got here.
        $this->addToAssertionCount(1);
    }

    public function testAllowsAccessToPublicProperties() : void
    {
        AccessScopeChecker::checkCallerScope(['object' => $this], new ParentClass(), 'public1');

        // Add to assertion count manually since we were successful when no exception was thrown and we got here.
        $this->addToAssertionCount(1);
    }

    public function testAllowsAccessFromSubClass() : void
    {
        AccessScopeChecker::checkCallerScope(
            ['object' => new InheritedPropertiesClass()],
            new ParentClass(),
            'protected1'
        );

        // Add to assertion count manually since we were successful when no exception was thrown and we got here.
        $this->addToAssertionCount(1);
    }

    public function testAllowsAccessFromSameClass() : void
    {
        AccessScopeChecker::checkCallerScope(
            ['object' => new ParentClass()],
            new ParentClass(),
            'private1'
        );

        // Add to assertion count manually since we were successful when no exception was thrown and we got here.
        $this->addToAssertionCount(1);
    }

    public function testAllowsAccessFromReflectionProperty() : void
    {
        AccessScopeChecker::checkCallerScope(
            ['object' => new ReflectionProperty(new ParentClass(), 'private1')],
            new ParentClass(),
            'private1'
        );

        // Add to assertion count manually since we were successful when no exception was thrown and we got here.
        $this->addToAssertionCount(1);
    }

    public function testDisallowsAccessFromGlobalOrFunctionScope() : void
    {
        $this->expectException('LazyProperty\\Exception\\InvalidAccess');
        AccessScopeChecker::checkCallerScope(
            [],
            new ParentClass(),
            'private1'
        );
    }

    public function testDisallowsPrivateAccessFromDifferentScope() : void
    {
        $this->expectException('LazyProperty\\Exception\\InvalidAccess');
        AccessScopeChecker::checkCallerScope(
            ['object' => $this],
            new ParentClass(),
            'private1'
        );
    }

    public function testDisallowsProtectedAccessFromDifferentScope() : void
    {
        $this->expectException('LazyProperty\\Exception\\InvalidAccess');
        AccessScopeChecker::checkCallerScope(
            ['object' => $this],
            new ParentClass(),
            'private1'
        );
    }
}
