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

namespace LazyProperty;


use LazyProperty\Exception\InvalidLazyProperty;
use LazyProperty\Exception\MissingLazyPropertyGetterException;
use LazyProperty\Util\AccessScopeChecker;

/**
 * Trait providing lazy initialization of object properties
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
trait LazyPropertiesTrait
{
    /**
     * @var bool[]|\Closure[] indexed by property name
     */
    private $lazyPropertyAccessors = [];

    /**
     * Initializes lazy properties so that first access causes their initialization via a getter
     *
     * @param string[] $lazyPropertyNames
     * @param bool     $checkLazyGetters
     *
     * @throws Exception\MissingLazyPropertyGetterException
     */
    protected function initLazyProperties(array $lazyPropertyNames, $checkLazyGetters = true)
    {
        foreach ($lazyPropertyNames as $lazyProperty) {
            if ($checkLazyGetters && ! method_exists($this, 'get' . $lazyProperty)) {
                throw MissingLazyPropertyGetterException::fromGetter($this, 'get' . $lazyProperty, $lazyProperty);
            }

            $this->lazyPropertyAccessors[$lazyProperty] = false;

            if (! isset($this->$lazyProperty)) {
                unset($this->$lazyProperty);
            }
        }
    }

    /**
     * Magic getter - initializes and gets a property
     *
     * @param string $name
     *
     * @throws InvalidLazyProperty if the requested lazy property does not exist
     */
    public function & __get($name)
    {
        if (! isset($this->lazyPropertyAccessors[$name])) {
            throw InvalidLazyProperty::nonExistingLazyProperty($this, $name);
        }

        $caller = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[1];

        // small optimization to avoid initializing reflection out of context
        if (! isset($caller['object']) || $caller['object'] !== $this) {
            AccessScopeChecker::checkCallerScope($caller, $this, $name);
        }

        $this->$name = null;
        $this->$name = $this->{'get' . $name}();

        return $this->$name;
    }
}
