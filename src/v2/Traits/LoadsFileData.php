<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Traits;

use Illuminate\Support\Collection;

trait LoadsFileData
{
    protected static array $addresses = [];

    private function __construct()
    {
        $regions = glob(__DIR__ . '/../regions/*');

        foreach ($regions as $file) {
            $region = "{$file}/data.php";

            $this->fromFile(include $region);
        }
    }

    public static function load(): void
    {
        if (static::$addresses === []) {
            new static();
        }
    }

    protected static function collect(array $contents): Collection
    {
        // todo: map zip codes per province
        /**
         * 'province' => [
         *    'name' => '...',
         *    'zip_codes' => [..],
         *    'municipalities' => [..],
         * ]
         */

        unset($contents['zip_data']);

        $wrapped = array_map(fn ($key): mixed => is_array($key) ? Collection::make($key) : $key, $contents);

        return Collection::make($wrapped);
    }

    protected function fromFile(array $contents): void
    {
        static::$addresses[$contents['region']] = static::collect($contents);
    }
}
