<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Components;

use KangBabi\PhZipCodes\v2\Enums\Address;
use KangBabi\PhZipCodes\v2\Traits\HydratesAddressComponents;

class Province
{
    use HydratesAddressComponents;

    protected Region $parent;

    public function __construct(
        protected string $name,
        array $container = [],
    ) {
        $this->key = Address::PROVINCE;

        $this->childKey = Address::MUNICIPALITY;

        $this->hydrate($container);
    }

    public static function make(string $name, array $container = []): static
    {
        return new static($name, $container);
    }

    public function setParent(Region $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): Region
    {
        return $this->parent;
    }
}
