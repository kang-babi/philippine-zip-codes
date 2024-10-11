<?php

namespace KangBabi\PhZipCodes;

class PhZipCode
{
  public $regions;

  public function __construct()
  {
    $this->regions =  [
      include __DIR__ . '/regions/regionncr/data.php',
      include __DIR__ . '/regions/region01/data.php',
      include __DIR__ . '/regions/region02/data.php',
      include __DIR__ . '/regions/region03/data.php',
      include __DIR__ . '/regions/region04a/data.php',
      include __DIR__ . '/regions/region04b/data.php',
      include __DIR__ . '/regions/region05/data.php',
      include __DIR__ . '/regions/region06/data.php',
      include __DIR__ . '/regions/region07/data.php',
      include __DIR__ . '/regions/region08/data.php',
      include __DIR__ . '/regions/region09/data.php',
      include __DIR__ . '/regions/region10/data.php',
      include __DIR__ . '/regions/region11/data.php',
      include __DIR__ . '/regions/region12/data.php',
      include __DIR__ . '/regions/region13/data.php',
      include __DIR__ . '/regions/region14/data.php',
      include __DIR__ . '/regions/region15/data.php',
    ];
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
