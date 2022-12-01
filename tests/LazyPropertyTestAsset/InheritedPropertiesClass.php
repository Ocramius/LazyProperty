<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use AllowDynamicProperties;
use LazyProperty\LazyPropertiesTrait;

/**
 * Mixed properties
 */
#[AllowDynamicProperties]
class InheritedPropertiesClass extends ParentClass
{
    use LazyPropertiesTrait;

    /** @param string[] $properties */
    public function initProperties(array $properties): void
    {
        $this->initLazyProperties($properties);
    }
}
