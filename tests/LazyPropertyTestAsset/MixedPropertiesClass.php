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

namespace LazyPropertyTestAsset;

use LazyProperty\LazyPropertiesTrait;

/**
 * Mixed properties test asset
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class MixedPropertiesClass
{
    use LazyPropertiesTrait;

    public $public1;
    public $public2;
    protected $protected1;
    protected $protected2;
    private $private1;
    private $private2;

    public function getProperty($propertyName)
    {
        return $this->$propertyName;
    }

    public function initProperties(array $properties)
    {
        $this->initLazyProperties($properties);
    }

    /**
     * @return mixed
     */
    private function getPrivate1()
    {
        return $this->private1 = 'private1';
    }

    /**
     * @return mixed
     */
    private function getPrivate2()
    {
        return $this->private2 = 'private2';
    }

    /**
     * @return mixed
     */
    protected function getProtected1()
    {
        return $this->protected1 = 'protected1';
    }

    /**
     * @return mixed
     */
    protected function getProtected2()
    {
        return $this->protected2 = 'protected2';
    }

    /**
     * @return mixed
     */
    public function getPublic1()
    {
        return $this->public1 = 'public1';
    }

    /**
     * @return mixed
     */
    public function getPublic2()
    {
        return $this->public2 = 'public2';
    }
}
