<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use LazyProperty\HasLazyProperties;

/**
 * Mixed properties
 */
class InheritedPropertiesClass extends ParentClass
{
    use HasLazyProperties;

    /**
     * @param string[] $properties
     */
    public function initProperties(array $properties) : void
    {
        $this->initLazyProperties($properties);
    }
}
