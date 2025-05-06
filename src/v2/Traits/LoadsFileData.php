<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Traits;

use KangBabi\PhZipCodes\v2\Components\Region;

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

    protected function fromFile(array $contents): void
    {
        $region = Region::make($contents['name'], $contents['region'], $contents['region_alt'], $contents['provinces']);

        static::$addresses[$contents['region']] = $region;
    }
}
