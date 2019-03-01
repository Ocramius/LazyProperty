<?php

declare(strict_types=1);

namespace LazyPropertyTest\Util;

use LazyProperty\Util\AccessScopeChecker;
use LazyPropertyTestAsset\InheritedPropertiesClass;
use LazyPropertyTestAsset\ParentClass;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Throwable;

/**
 * Tests for {@see \LazyProperty\Util\AccessScopeChecker}
 *
 * @covers \LazyProperty\Util\AccessScopeChecker
 */
class AccessScopeCheckerTest extends TestCase
{
    public function testAllowsAccessFromSameInstance() : void
    {
        try {
            AccessScopeChecker::checkCallerScope(['object' => $this], $this, 'backupGlobals');
        } catch (Throwable $exception) {
            $this->fail('Unexpected exception.');
        }

        $this->assertTrue(true);
    }

    public function testAllowsAccessToPublicProperties() : void
    {
        try {
            AccessScopeChecker::checkCallerScope(['object' => $this], new ParentClass(), 'public1');
        } catch (Throwable $exception) {
            $this->fail('Unexpected exception.');
        }

        $this->assertTrue(true);
    }

    public function testAllowsAccessFromSubClass() : void
    {
        try {
            AccessScopeChecker::checkCallerScope(
                ['object' => new InheritedPropertiesClass()],
                new ParentClass(),
                'protected1'
            );
        } catch (Throwable $exception) {
            $this->fail('Unexpected exception.');
        }

        $this->assertTrue(true);
    }

    public function testAllowsAccessFromSameClass() : void
    {
        try {
            AccessScopeChecker::checkCallerScope(
                ['object' => new ParentClass()],
                new ParentClass(),
                'private1'
            );
        } catch (Throwable $exception) {
            $this->fail('Unexpected exception.');
        }

        $this->assertTrue(true);
    }

    public function testAllowsAccessFromReflectionProperty() : void
    {
        try {
            AccessScopeChecker::checkCallerScope(
                ['object' => new ReflectionProperty(new ParentClass(), 'private1')],
                new ParentClass(),
                'private1'
            );
        } catch (Throwable $exception) {
            $this->fail('Unexpected exception.');
        }

        $this->assertTrue(true);
    }

    public function testDisallowsAccessFromGlobalOrFunctionScope() : void
    {
        $this->expectException('LazyProperty\\Exception\\InvalidAccessException');
        AccessScopeChecker::checkCallerScope(
            [],
            new ParentClass(),
            'private1'
        );
    }

    public function testDisallowsPrivateAccessFromDifferentScope() : void
    {
        $this->expectException('LazyProperty\\Exception\\InvalidAccessException');
        AccessScopeChecker::checkCallerScope(
            ['object' => $this],
            new ParentClass(),
            'private1'
        );
    }

    public function testDisallowsProtectedAccessFromDifferentScope() : void
    {
        $this->expectException('LazyProperty\\Exception\\InvalidAccessException');
        AccessScopeChecker::checkCallerScope(
            ['object' => $this],
            new ParentClass(),
            'private1'
        );
    }
}
