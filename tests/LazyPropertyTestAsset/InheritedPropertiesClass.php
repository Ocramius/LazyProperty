<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use LazyProperty\LazyPropertiesTrait;

/**
 * Mixed properties
 */
class InheritedPropertiesClass extends ParentClass
{
    use LazyPropertiesTrait;

    public function initProperties(array $properties) : void
    {
        $this->initLazyProperties($properties);
    }
}
