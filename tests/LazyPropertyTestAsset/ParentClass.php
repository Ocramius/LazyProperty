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

declare(strict_types=1);

namespace LazyPropertyTestAsset;

/**
 * Base class with mixed properties
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class ParentClass
{
    public $public1;
    public $public2;
    protected $protected1;
    protected $protected2;
    private $private1;
    private $private2;

    public function getProperty(string $propertyName): string
    {
        return $this->$propertyName;
    }

    /**
     * @return string
     */
    private function getPrivate1(): string
    {
        return $this->private1 = 'private1';
    }

    /**
     * @return string
     */
    private function getPrivate2(): string
    {
        return $this->private2 = 'private';
    }

    /**
     * @return string
     */
    protected function getProtected1(): string
    {
        return $this->protected1 = 'protected1';
    }

    /**
     * @return string
     */
    protected function getProtected2(): string
    {
        return $this->protected2 = 'protected2';
    }

    /**
     * @return string
     */
    public function getPublic1(): string
    {
        return $this->public1 = 'public1';
    }

    /**
     * @return string
     */
    public function getPublic2(): string
    {
        return $this->public2 = 'public2';
    }
}
