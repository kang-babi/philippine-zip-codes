<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Components;

use KangBabi\PhZipCodes\v2\Enums\Address;
use KangBabi\PhZipCodes\v2\Traits\HydratesAddressComponents;

class Region
{
    use HydratesAddressComponents;

    public function __construct(
        protected string $name,
        protected string $code,
        protected string $alt,
        array $container = [],
    ) {
        $this->key = Address::REGION;

        $this->childKey = Address::PROVINCE;

        $this->hydrate($container);
    }

    public static function make(string $name, string $code, string $alt, array $container = []): static
    {
        return new static($name, $code, $alt, $container);
    }
}
