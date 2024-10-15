<?php

require __DIR__ . '/vendor/autoload.php';

use KangBabi\PhZipCodes\PhZipCode;

$zipCodes = new PhZipCode();

echo '<pre>';
print_r($zipCodes->getMunicipalities('bicol region', 'camarines sur'));
echo '</pre>';

// $merged = array_merge_recursive($data);

// ksort($merged);

// foreach ($merged as $municipality => $barangays) {
//   // $municipality = str_replace(' ', '_', $municipality);
//   # replace non-alphanumeric with underscore
//   $municipality = preg_replace('/[^a-zA-Z0-9]/', '_', $municipality);

//   echo '<pre>';
//   echo "{$municipality}: , \"barangays\" => [";
//   echo implode(', ', array_map(fn($item) => '"' . $item . '"', $barangays['barangay_list']));
//   echo "]";
//   echo "</pre>";
// }
