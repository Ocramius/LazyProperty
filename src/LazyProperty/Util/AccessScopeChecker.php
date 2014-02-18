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

namespace LazyProperty\Util;


use LazyProperty\Exception\InvalidAccessException;

/**
 * Utility class to identify scope access violations
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class AccessScopeChecker
{
    /**
     * Utility used to verify that access to lazy properties is not happening from outside allowed scopes
     *
     * @internal
     * @private
     *
     * @param array  $caller the caller array as from the debug stack trace entry
     * @param object $instance
     * @param string $property
     *
     * @return null
     *
     * @throws \LazyProperty\Exception\InvalidAccessException
     */
    public static function checkCallerScope(array $caller, $instance, $property)
    {
        $reflectionProperty = new \ReflectionProperty($instance, $property);

        if (! $reflectionProperty->isPublic()) {
            if (! isset($caller['object'])) {
                throw InvalidAccessException::invalidContext(null, $instance, $property);
            }

            $caller        = $caller['object'];
            $callerClass   = get_class($caller);
            $instanceClass = get_class($instance);

            if ($callerClass === $instanceClass
                || ($reflectionProperty->isProtected() && is_subclass_of($callerClass, $instanceClass))
                || $callerClass === 'ReflectionProperty'
                || is_subclass_of($callerClass, 'ReflectionProperty')
            ) {
                return;
            }

            throw InvalidAccessException::invalidContext($caller, $instance, $property);
        }
    }
}
