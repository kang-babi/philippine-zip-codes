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

  public function subAddress(): string
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
}
