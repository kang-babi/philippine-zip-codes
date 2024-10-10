<?php

namespace KangBabi\PhZipCodes;

class PhZipCode
{
  public $regions;

  public function __construct()
  {
    # recursively include all region data.php files
    $this->regions = array_merge(
      include __DIR__ . '/region01',
      include __DIR__ . '/region02',
      include __DIR__ . '/region03',
      include __DIR__ . '/region04a',
      include __DIR__ . '/region04b',
      include __DIR__ . '/region05',
      include __DIR__ . '/region06',
      include __DIR__ . '/region07',
      include __DIR__ . '/region08',
      include __DIR__ . '/region09',
      include __DIR__ . '/region10',
      include __DIR__ . '/region11',
      include __DIR__ . '/region12',
      include __DIR__ . '/region13',
      include __DIR__ . '/region14',
      include __DIR__ . '/region15',
      include __DIR__ . '/regionncr',
    );
  }
  public static function getRegions()
  {
    return include __DIR__ . '/includes/regions/data.php';
  }

  public static function getRegion($region)
  {
    $regions = self::getRegions();
    return $regions[$region] ?? null;
  }

  public static function getProvinces($region)
  {
    $regionData = self::getRegion($region);
    return $regionData['provinces'] ?? [];
  }

  public static function getProvince($region, $province)
  {
    $provinces = self::getProvinces($region);
    return $provinces[$province] ?? null;
  }

  public static function getMunicipalities($region, $province)
  {
    $provinceData = self::getProvince($region, $province);
    return $provinceData['municipalities'] ?? [];
  }

  public static function getMunicipality($region, $province, $municipality)
  {
    $municipalities = self::getMunicipalities($region, $province);
    return $municipalities[$municipality] ?? null;
  }

  public static function getBarangays($region, $province, $municipality)
  {
    $municipalityData = self::getMunicipality($region, $province, $municipality);
    return $municipalityData['barangays'] ?? [];
  }

  public static function getBarangay($region, $province, $municipality, $barangay)
  {
    $barangays = self::getBarangays($region, $province, $municipality);
    return $barangays[$barangay] ?? null;
  }

  public static function getZipCodes($region, $province, $municipality, $barangay)
  {
    $barangayData = self::getBarangay($region, $province, $municipality, $barangay);
    return $barangayData['zip_codes'] ?? [];
  }

  public static function getZipCode($region, $province, $municipality, $barangay, $zipCode)
  {
    $zipCodes = self::getZipCodes($region, $province, $municipality, $barangay);
    return $zipCodes[$zipCode] ?? null;
  }
}
