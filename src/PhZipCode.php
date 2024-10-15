<?php

namespace KangBabi\PhZipCodes;

class PhZipCode
{
  public $regions;

  public function __construct()
  {
    $this->regions =  [
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
      include __DIR__ . '/regions/regionncr/data.php',
    ];
  }

  public function u($string)
  {
    return strtoupper($string);
  }

  public function getZipCodes()
  {
    return array_map(function ($region) {
      return [
        'region' => $this->u($region['region']),
        'region_alt' => $this->u($region['region_alt']),
        'name' => $this->u($region['name']),
        'provinces' => array_map(function ($province) use ($region) {
          return [
            'region' => $this->u($region['region']),
            'province' => $this->u($province['province']),
            'municipality' => $this->u($province['municipality']),
            'address' => $this->u(implode(', ', [
              $region['region'],
              $province['province'],
              $province['municipality'],
            ])),
            'zip_code' => $province['zip_code'],
          ];
        }, $region['provinces']),
      ];
    }, $this->regions);
  }

  public function getZipCodesList()
  {
    return array_merge(
      ...array_map(function ($region) {
        return array_map(function ($province) use ($region) {
          return [
            'region' => $this->u($region['region']) . '(' . $this->u($region['name']) . ')',
            'province' => $this->u($province['province']),
            'municipality' => $this->u($province['municipality']),
            'address' => $this->u(implode(', ', [
              $region['region'],
              $province['province'],
              $province['municipality'],
            ])),
            'zip_code' => $province['zip_code'],
          ];
        }, $region['provinces']);
      }, $this->regions)
    );
  }

  public function getRegions()
  {
    return array_map(function ($region) {
      return [
        'region' => $this->u($region['region']),
        'region_alt' => $this->u($region['region_alt']),
        'name' => $this->u($region['name']),
      ];
    }, $this->regions);
  }

  public function getRegion($region)
  {
    return array_merge(
      ...array_filter($this->getZipCodes(), function ($province) use ($region) {
        $region = $this->u($region);
        [
          $province['region'],
          $province['region_alt'],
          $province['name']
        ] = [
          $this->u($province['region']),
          $this->u($province['region_alt']),
          $this->u($province['name'])
        ];

        return $province['region'] === $region || $province['region_alt'] === $region || $province['name'] === $region;
      })
    );
  }

  public function getProvinces($region)
  {
    if ($provinces = $this->getRegion($region)) {
      return $provinces['provinces'];
    }

    return [];
  }

  public function getMunicipalities($region, $province)
  {
    if ($provinces = $this->getProvinces($region)) {
      return array_merge(
        array_filter($provinces, function ($item) use ($province) {
          return $this->u($item['province']) === $this->u($province);
        })
      );
    }

    return [];
  }
}
