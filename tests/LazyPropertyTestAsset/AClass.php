<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use LazyProperty\HasLazyProperties;

/**
 * Mixed properties
 */
class AClass
{
    use HasLazyProperties;

    private string $private;

    public function initALazyProperties() : void
    {
        $this->initLazyProperties(['private']);
    }

    private function getPrivate() : string
    {
        return $this->private ?: $this->private = self::class;
    }
}
