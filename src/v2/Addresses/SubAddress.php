<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Addresses;

use Illuminate\Support\Collection;
use KangBabi\PhZipCodes\v2\Enums\Address;

class SubAddress
{
    protected static self $instance;

    public function __construct(
        protected Collection $container,
        protected Address $address,
        protected bool $include = false,
    ) {
        // Remove the address from the contents if not included
        if (! $this->include) {
            $this->container->except($this->address->components());
        }
    }

    /**
     * Create a new instance with the given contents and Address.
     *
     * @param \KangBabi\PhZipCodes\v2\Enums\Address $Address type of content
     * @param bool $include whether to include the Address in the contents
     */
    public static function make(Collection $contents, Address $Address, bool $include = false): static
    {
        static::$instance = new self($contents, $Address, $include);

        return static::$instance;
    }

    public function subunits(): Collection
    {
        return $this->container->get($this->address->components());
    }

    /**
     * Get the Address data.
     */
    public function get(): Collection
    {
        return $this->container;
    }

    /**
     * Get the provinces of the region.
     *
     * @param bool $barangays whether to include barangays in the provinces
     */
    public function provinces(bool $barangays = false): static
    {
        $provinces = Collection::make($this->container->get('provinces', []));

        $provinces = $provinces
            ->when(
                !$barangays,
                fn(Collection $province): Collection => collect($province)->except(['barangays'])
            )
            ->map(fn(array $province): array => collect($province)->except(['zip_code'])->toArray());

        return self::make($provinces, Address::PROVINCE);
    }

    public function municipalities(bool $barangays = false): static
    {
        $municipalities = Collection::make($this->container->get('municipalities', []));

        if (!$barangays) {
            $municipalities = $municipalities->map(fn($municipality) => collect($municipality)->except(['barangays'])->toArray());
        }

        return self::make($municipalities, Address::MUNICIPALITY);
    }
}
