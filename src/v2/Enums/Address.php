<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Enums;

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
    public function component(): string
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
