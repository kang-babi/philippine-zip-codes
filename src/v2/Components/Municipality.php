<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Components;

use KangBabi\PhZipCodes\v2\Enums\Address;
use KangBabi\PhZipCodes\v2\Traits\HydratesAddressComponents;

class Municipality
{
    use HydratesAddressComponents;

    protected Province $parent;

    public function __construct(
        protected string $name,
        array $container = [],
    ) {
        $this->key = Address::MUNICIPALITY;

        $this->childKey = Address::BARANGAY;

        $this->hydrate($container);
    }

    public static function make(string $name, array $container): static
    {
        return new static($name, $container);
    }

    public function setParent(Province $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): Province
    {
        return $this->parent;
    }
}
