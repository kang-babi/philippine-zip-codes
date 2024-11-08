<?php

namespace KangBabi\PhZipCodes;

use Illuminate\Support\Collection;

function _($string)
{
  return mb_strtoupper($string);
}

class PhAddress extends Collection
{
  protected static $data = null;
  protected static $container = null;

  protected static function fetch(): array
  {
    $data = [
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
      # static::mapNCRValues()
    ];

    # recursively set all values to uppercase
    array_walk_recursive($data, function (&$value) {
      $value = mb_strtoupper($value);
    });

    $data = array_map(function ($region) {
      $region['provinces'] = array_map(function ($province) use ($region) {
        $province['address'] = implode(', ', [
          $region['region'],
          $province['province'],
          $province['municipality']
        ]);

        return $province;
      }, $region['provinces']);

      // if (in_array('zip_data', array_keys($region))) {
      //   $region['zip_data'] = array_map(
      //     function ($zip) use ($region) {
      //       $zip['region'] = $region;

      //       return $zip;
      //     },
      //     $region['zip_data']
      //   );
      // }

      return $region;
    }, $data);

    return $data;
  }

  public static function mapNCRValues(): array
  {
    $data = include __DIR__ . '/regions/regionncr/data.php';

    $provinces = $data['provinces'];
    $zip_data = $data['zip_data'];

    foreach ($provinces as $index => $province) {
      foreach ($zip_data as $index_ => $zip) {
        if ($zip['city'] === $province['municipality']) {
          $provinces[$index]['zip_codes'][] = [
            'zip_code' => $zip['zip_code'],
            'location' => _($zip['municipality'])
          ];

          unset($zip_data[$index_]); # for performance
        }
      }
    }

    return [
      'region' => $data['region'],
      'region_alt' => $data['region_alt'],
      'name' => $data['name'],
      'provinces' => $provinces
    ];
  }

  protected static function load(): static
  {
    if (!static::$data) {
      static::$data = static::fetch();
    }

    return new static(static::$data);
  }

  protected static function loadFlat(): static
  {
    $flatData = static::load()
      ->map(function ($region) {
        return collect($region['provinces'])->map(function ($province) use ($region) {
          $barangays = isset($province['barangays']) ? $province['barangays'] : [];

          # [$key, $data] = $region['region'] === 'NCR' ?
          #   ['zip_codes', $province['zip_codes'] ?? []] :
          #   ['zip_code', $province['zip_code'] ?? []];

          return collect([
            'region' => $region['region'],
            'region_alt' => $region['region_alt'],
            'name' => $region['name'],
            'province' => $province['province'],
            'municipality' => $province['municipality'],
            'address' => _(implode(', ', [
              $region['region'],
              $province['province'],
              $province['municipality']
            ])),
            'barangays' => collect($barangays)->sort(),
            # $key => $data,
          ]);
        });
      })
      ->collapse();

    return new static($flatData);
  }

  public static function regionsList(): static
  {
    return static::load()
      ->map(fn($r) => [
        'region' => $r['region'],
        'name' => $r['name']
      ]);
  }

  public static function provincesList(?string $region = ''): static
  {
    return static::load()
      ->when($region, fn($r) => $r->filter(
        fn($r) => _($r['region']) === _($region)
      ))
      ->map(fn($r) => $r['provinces'])
      ->collapse()
      ->pluck('province')
      ->unique()
      ->values();
  }

  public static function municipalitiesList(?string $province = ''): static
  {
    return static::load()
      ->map(fn($r) => $r['provinces'])
      ->collapse()
      ->when($province, fn($r) => $r->filter(
        fn($r) => _($r['province']) === _($province)
      ))
      ->pluck('municipality')
      ->unique();
  }

  public static function barangaysList(): static
  {
    return static::load()
      ->map(fn($r) => $r['provinces'])
      ->collapse()
      ->pluck('barangays')
      ->collapse();
  }

  public static function data(bool $isFlat = false): static
  {
    if ($isFlat) {
      return static::loadFlat();
    }

    return static::load();
  }

  public static function region(?string $region = ''): static
  {
    if (trim($region) === '') {
      return static::regionsList();
    }

    static::$container = static::load()
      ->filter(fn($r) => in_array(_($region), [_($r['region']), _($r['region_alt']), _($r['name'])]));

    return new static(static::$container);
  }

  public static function province(?string $province = ''): static
  {
    if (trim($province) === '') {
      return new static(static::$container ? static::$container
        ->map(fn($r) => $r['provinces'])
        ->collapse()
        ->pluck('province')
        ->unique()
        ->values() : []);
    }

    static::$container = static::$container ? static::$container
      ->map(fn($r) => $r['provinces'])
      ->collapse()
      ->filter(
        fn($r) =>
        _($r['province']) === _($province)
          && !empty($r['barangays'])
      ) : [];

    return new static(static::$container);
  }

  public static function municipality(?string $municipality = ''): static
  {
    if (trim($municipality) === '') {
      return new static(static::$container ? static::$container
        ->pluck('municipality')
        ->unique() : []);
    }

    static::$container = static::$container ? static::$container
      ->filter(fn($r) => _($r['municipality']) === _($municipality)) : [];

    return new static(static::$container);
  }

  public static function flush(): void
  {
    static::$data = null;
    static::$container = null;
    static::load();
  }
}
