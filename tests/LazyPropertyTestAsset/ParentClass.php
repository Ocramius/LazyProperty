<?php

declare(strict_types=1);

namespace LazyPropertyTestAsset;

/**
 * Base class with mixed properties
 */
class ParentClass
{
    /** @var string */
    public $public1;
    /** @var string */
    public $public2;
    /** @var string */
    protected $protected1;
    /** @var string */
    protected $protected2;

    // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements
    /** @var string */
    private $private1;
    /** @var string */
    private $private2;
    // phpcs:enable

    public function getProperty(string $propertyName) : string
    {
        return $this->$propertyName;
    }

    // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements
    private function getPrivate1() : string
    {
        return $this->private1 = 'private1';
    }

    private function getPrivate2() : string
    {
        return $this->private2 = 'private';
    }
    // phpcs:enable

    protected function getProtected1() : string
    {
        return $this->protected1 = 'protected1';
    }

    protected function getProtected2() : string
    {
        return $this->protected2 = 'protected2';
    }

    public function getPublic1() : string
    {
        return $this->public1 = 'public1';
    }

    public function getPublic2() : string
    {
        return $this->public2 = 'public2';
    }
}
