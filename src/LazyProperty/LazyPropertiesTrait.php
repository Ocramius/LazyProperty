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
     * @param string[] $lazyPropertyNames
     * @param bool     $checkLazyPropertyVisibility
     */
    protected function initLazyProperties(
        array $lazyPropertyNames,
        $checkLazyPropertyVisibility = true
    ) {
        foreach ($lazyPropertyNames as $lazyProperty) {
            $this->lazyPropertyAccessors[$lazyProperty] = false;

            if ($checkLazyPropertyVisibility) {
                $reflectionProperty = new \ReflectionProperty($this, $lazyProperty);

                if ($reflectionProperty->isPrivate()
                    && $reflectionProperty->getDeclaringClass()->getName() !== get_class($this)
                ) {
                    $declaringClassName = $reflectionProperty->getDeclaringClass()->getName();

                    $this->lazyPropertyAccessors[$lazyProperty] = \Closure::bind(
                        function & () use ($lazyProperty) {
                            $this->$lazyProperty = $this->{'get' . $lazyProperty}();

                            return $this->$lazyProperty;
                        },
                        $this,
                        $declaringClassName
                    );

                    \Closure::bind(
                        function & () use ($lazyProperty) {
                            if (! isset($this->$lazyProperty)) {
                                unset($this->$lazyProperty);
                            }
                        },
                        $this,
                        $declaringClassName
                    )->__invoke();

                    continue;
                }

                if (! isset($this->$lazyProperty)) {
                    unset($this->$lazyProperty);
                }
            }
        }
    }

    /**
     * Magic getter - initializes and gets a property
     *
     * @param string $name
     */
    public function & __get($name)
    {
        if (isset($this->lazyPropertyAccessors[$name])) {
            if ($this->lazyPropertyAccessors[$name]) {
                $accessor = $this->lazyPropertyAccessors[$name];

                return $accessor();
            }

            $this->$name = $this->{'get' . $name}();
        }

        return $this->$name;
    }
}
