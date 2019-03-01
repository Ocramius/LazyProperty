<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use LazyProperty\LazyPropertiesTrait;

/**
 * Test asset with lazy getters
 */
class LazyGetterClass
{
    use LazyPropertiesTrait;

    public $property;

    public function initProperties(array $properties) : void
    {
        $this->initLazyProperties($properties);
    }

    public function getProperty() : string
    {
        if ($this->property === null) {
            return $this->property = 'property';
        }

        return $this->property;
    }
}
