<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use AllowDynamicProperties;
use LazyProperty\LazyPropertiesTrait;

/**
 * Mixed properties test asset
 */
#[AllowDynamicProperties]
class MixedPropertiesClass
{
    use LazyPropertiesTrait;

    public string|null $public1       = null;
    public string|null $public2       = null;
    protected string|null $protected1 = null;
    protected string|null $protected2 = null;
    private string|null $private1     = null;
    private string|null $private2     = null;

    public function getProperty(string $propertyName): string|null
    {
        return $this->$propertyName;
    }

    /** @param string[] $properties */
    public function initProperties(array $properties): void
    {
        $this->initLazyProperties($properties);
    }

    private function getPrivate1(): string
    {
        return $this->private1 = 'private1';
    }

    private function getPrivate2(): string
    {
        return $this->private2 = 'private2';
    }

    protected function getProtected1(): string
    {
        return $this->protected1 = 'protected1';
    }

    protected function getProtected2(): string
    {
        return $this->protected2 = 'protected2';
    }

    public function getPublic1(): string
    {
        return $this->public1 = 'public1';
    }

    public function getPublic2(): string
    {
        return $this->public2 = 'public2';
    }
}
