<?php

namespace KangBabi\PhZipCodes;

function u($string)
{
  return mb_strtoupper($string);
}

class PhZipCode
{
  public $regions;
  public $ncr;

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
      # include __DIR__ . '/regions/regionncr/data.php',
    ];

    $this->ncr = include __DIR__ . '/regions/regionncr/data.php';
  }

  public function getZipCodes()
  {
    return array_map(function ($region) {
      return [
        'region' => u($region['region']),
        'region_alt' => u($region['region_alt']),
        'name' => u($region['name']),
        'provinces' => array_map(function ($province) use ($region) {
          return [
            'region' => u($region['region']),
            'province' => u($province['province']),
            'municipality' => u($province['municipality']),
            'address' => u(implode(', ', [
              $region['region'],
              $province['province'],
              $province['municipality'],
            ])),
            'zip_code' => $province['zip_code'],
          ];
        }, $region['provinces']),
      ];
    }, $this->regions + [$this->ncr]);
  }

  public function getZipCodesList(?bool $hasBarangay = false)
  {
    return array_merge(
      ...array_map(function ($region) use ($hasBarangay) {
        return array_map(function ($province) use ($region, $hasBarangay) {
          return [
            'region' => u($region['region']) . ' (' . u($region['name']) . ')',
            'province' => u($province['province']),
            'municipality' => u($province['municipality']),
            'address' => u(
              implode(', ', [
                $region['region'],
                $province['province'],
                $province['municipality'],
              ])
            ),
            'zip_code' => $province['zip_code'],
            # optional key for barangay
            'barangays' => $hasBarangay ? (array_key_exists('barangays', $province) ? $province['barangays'] : []) : [],
          ];
        }, $region['provinces']);
      }, $this->regions)
    );
  }

  public function getRegions()
  {
    return array_map(function ($region) {
      return [
        'region' => u($region['region']),
        'region_alt' => u($region['region_alt']),
        'name' => u($region['name']),
      ];
    }, $this->regions);
  }

  public function getRegion($region)
  {
    return array_merge(
      ...array_filter($this->getZipCodes(), function ($province) use ($region) {
        $region = u($region);
        [
          $province['region'],
          $province['region_alt'],
          $province['name']
        ] = [
          u($province['region']),
          u($province['region_alt']),
          u($province['name'])
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
          return u($item['province']) === u($province);
        })
      );
    }

    return [];
  }

  public function getBarangays($region, $province, $municipality)
  {
    if ($provinces = $this->getMunicipalities($region, $province)) {
      return array_merge(
        ...array_map(
          function ($municipality) {
            return $municipality['barangays'];
          },
          array_filter(
            $this->getZipCodesList(true),
            function ($item) use ($municipality) {
              return u($item['municipality']) === u($municipality);
            }
          )
        )
      );
    }

    return [];
  }

  public function getZipCode($zipCode)
  {
    return array_merge(
      ...array_filter($this->getZipCodesList(true), function ($item) use ($zipCode) {
        return u($item['zip_code']) === u($zipCode);
      }) ?: []
    );
  }
}
