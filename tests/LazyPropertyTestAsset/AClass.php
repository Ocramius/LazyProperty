<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use LazyProperty\LazyPropertiesTrait;

/**
 * Mixed properties
 */
class AClass
{
    use LazyPropertiesTrait;

    private ?string $private;

    public function initALazyProperties(): void
    {
        $this->initLazyProperties(['private']);
    }

    private function getPrivate(): string
    {
        return $this->private ?: $this->private = self::class;
    }
}
