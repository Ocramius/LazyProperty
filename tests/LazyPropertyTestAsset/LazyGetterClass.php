<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use AllowDynamicProperties;
use LazyProperty\LazyPropertiesTrait;

/**
 * Test asset with lazy getters
 */
#[AllowDynamicProperties]
class LazyGetterClass
{
    use LazyPropertiesTrait;

    public string|null $property;

    /** @param string[] $properties */
    public function initProperties(array $properties): void
    {
        $this->initLazyProperties($properties);
    }

    public function getProperty(): string
    {
        if ($this->property === null) {
            return $this->property = 'property';
        }

        return $this->property;
    }
}
