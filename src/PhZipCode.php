<?php

namespace KangBabi\PhZipCodes;

class PhZipCode
{
  public $regions;

  public function __construct()
  {
    $this->regions =  [
      include __DIR__ . '/regions/region01/data.php',
      // include __DIR__ . '/regions/region02/data.php',
      // include __DIR__ . '/regions/region03/data.php',
      // include __DIR__ . '/regions/region04a/data.php',
      // include __DIR__ . '/regions/region04b/data.php',
      // include __DIR__ . '/regions/region05/data.php',
      // include __DIR__ . '/regions/region06/data.php',
      // include __DIR__ . '/regions/region07/data.php',
      // include __DIR__ . '/regions/region08/data.php',
      // include __DIR__ . '/regions/region09/data.php',
      // include __DIR__ . '/regions/region10/data.php',
      // include __DIR__ . '/regions/region11/data.php',
      // include __DIR__ . '/regions/region12/data.php',
      // include __DIR__ . '/regions/region13/data.php',
      // include __DIR__ . '/regions/region14/data.php',
      // include __DIR__ . '/regions/region15/data.php',
      // include __DIR__ . '/regions/regionncr/data.php',
    ];
  }

  public function getZipCodes()
  {
    return array_map(function ($region) {
      return [
        'region' => strtoupper($region['region']),
        'region_alt' => strtoupper($region['region_alt']),
        'name' => strtoupper($region['name']),
        'provinces' => array_map(function ($province) use ($region) {
          return [
            'region' => strtoupper($region['region']) . ' (' . strtoupper($region['name']) . ')',
            'province' => strtoupper($province['province']),
            'municipality' => strtoupper($province['municipality']),
            'zip_code' => $province['zip_code'],
          ];
        }, $region['provinces']),
      ];
    }, $this->regions);
  }
}
