<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

use AllowDynamicProperties;
use LazyProperty\LazyPropertiesTrait;

/**
 * Mixed properties
 */
#[AllowDynamicProperties]
class BClass extends AClass
{
    use LazyPropertiesTrait;

    private string|null $private;

    public function initBLazyProperties(): void
    {
        $this->initLazyProperties(['private']);
    }

    private function getPrivate(): string
    {
        return $this->private ?: $this->private = self::class;
    }
}
