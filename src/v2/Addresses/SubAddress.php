<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Addresses;

use Illuminate\Support\Collection;
use KangBabi\PhZipCodes\v2\Enums\Key;

class SubAddress
{
    protected static self $instance;

    public function __construct(
        protected Collection $container,
        protected Key $key,
        protected bool $include = false,
    ) {
        // Remove the key from the contents if not included
        if (! $this->include) {
            $this->container->except($this->key->key());
        }

        // Remove the zip code if the key is region
        if ($this->key === Key::REGION) {
            $this->container->except(Key::ZIP_CODE->key());
        }
    }

    /**
     * Get the Address data.
     */
    public function get(): Collection
    {
        return $this->container;
    }

    /**
     * Create a new instance with the given contents and key.
     * 
     * @param \KangBabi\PhZipCodes\v2\Enums\Key $key type of content
     * @param bool $include whether to include the key in the contents
     */
    public static function make(Collection $contents, Key $key, bool $include = false): self
    {
        static::$instance = new self($contents, $key, $include);

        return static::$instance;
    }

    /**
     * Get the provinces of the region.
     * 
     * @param bool $barangays whether to include barangays in the provinces
     */
    public function provinces(bool $barangays = false): self
    {
        $provinces = Collection::make($this->container->get('provinces', []));

        if (!$barangays) {
            $provinces = $provinces->map(fn($province) => collect($province)->except(['barangays'])->toArray());
        }

        return self::make($provinces, Key::PROVINCE);
    }
}
