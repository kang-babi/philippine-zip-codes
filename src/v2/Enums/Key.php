<?php

declare(strict_types=1);

namespace KangBabi\PhZipCodes\v2\Enums;

enum Key: string
{
    case NULL = '';

    case REGION = 'region';
    case PROVINCE = 'province';
    case MUNICIPALITY = 'municipality';
    case BARANGAY = 'barangay';

    case ZIP_CODE = 'zip_code';

    public function key(): string
    {
        return match ($this) {
            self::REGION => 'provinces',
            self::PROVINCE => 'municipalities',
            self::MUNICIPALITY => 'barangays',
            self::ZIP_CODE => 'zip_data',
            default => 'zip_data',
        };
    }
}
