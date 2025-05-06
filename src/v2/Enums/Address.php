<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Enums;

use KangBabi\PhZipCodes\v2\Components\Barangay;
use KangBabi\PhZipCodes\v2\Components\Municipality;
use KangBabi\PhZipCodes\v2\Components\Province;
use KangBabi\PhZipCodes\v2\Components\Region;

enum Address: string
{
    case NULL = '';

    case REGION = 'region';
    case PROVINCE = 'province';
    case MUNICIPALITY = 'municipality';
    case BARANGAY = 'barangay';

    case ZIP_CODE = 'zip_code';

    /**
     * Get the components key for the current address component.
     */
    public function components(): string
    {
        return match ($this) {
            self::REGION => 'provinces',
            self::PROVINCE => 'municipalities',
            self::MUNICIPALITY => 'barangays',
            self::ZIP_CODE => 'zip_data',
            self::BARANGAY => '',
            default => 'zip_data',
        };
    }

    /**
     * Get the class representation of the key.
     *
     * @return class-string
     */
    public function class(): string
    {
        return match ($this) {
            self::REGION => Region::class,
            self::PROVINCE => Province::class,
            self::MUNICIPALITY => Municipality::class,
            self::BARANGAY => Barangay::class,
            default => '',
        };
    }

    public function componentKey(): string
    {
        return match ($this) {
            self::REGION => 'province',
            self::PROVINCE => 'municipality',
            default => '',
        };
    }

    /**
     * Get the component class representation of the key.
     *
     * @return class-string
     */
    public function componentClass(): string
    {
        return match ($this) {
            self::REGION => Province::class,
            self::PROVINCE => Municipality::class,
            self::MUNICIPALITY => Barangay::class,
            default => ''
        };
    }

    /**
     * Signifies the breakdown of the address from its hierarchy
     *
     * @return string[]
     */
    public function segments(): array
    {
        return match ($this) {
            self::PROVINCE => ['region'],
            self::MUNICIPALITY => ['region', 'province'],
            self::BARANGAY => ['region', 'province', 'municipality'],
            default => [],
        };
    }

    /**
     * Alternative key for municipality in NCR case
     */
    public function alternative(): ?string
    {
        // todo: make this fit for NCR case of cities instead of municipalities
        return match ($this) {
            self::MUNICIPALITY => 'city',
            default => $this->value,
        };
    }
}
