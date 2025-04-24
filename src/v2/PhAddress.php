<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2;

use Illuminate\Support\Collection;
use KangBabi\PhZipCodes\v2\Addresses\SubAddress;
use KangBabi\PhZipCodes\v2\Enums\Key;
use KangBabi\PhZipCodes\v2\Traits\LoadsFileData;

class PhAddress
{
    use LoadsFileData;

    /**
     * Check if the address belongs to the given key.
     */
    protected static function belongsTo(Collection $address, string $key): bool
    {
        return
            $address->get('region') === $key ||
            $address->get('region_alt') === $key ||
            $address->get('name') === $key;
    }

    /**
     * Guess the region from the given key.
     * 
     * @param string $region the region name or code
     */
    protected static function guessRegion(string $region): array
    {
        $region = mb_strtoupper($region);

        $region = array_filter(static::$addresses, fn($address) => static::belongsTo($address, $region));

        return $region[0] ?? [];
    }

    /**
     * Get the raw addresses from the files.
     */
    public static function all(): Collection
    {
        return Collection::make(static::$addresses);
    }

    /**
     * Get the regions of the Philippines.
     * 
     * @param bool $provinces whether to include provinces in the region
     */
    public static function regions(bool $provinces = false): Collection
    {
        return Collection::make(static::$addresses)
            ->map(fn($region): SubAddress => SubAddress::make($region, Key::REGION, $provinces));
    }

    /**
     * Get the region by name.
     * 
     * @param bool $provinces whether to include provinces in the region
     */
    public static function region(string $region, bool $provinces = false): SubAddress
    {
        $region = static::$addresses[$region] ?? static::guessRegion($region);

        return SubAddress::make($region, Key::REGION, $provinces);
    }
}
