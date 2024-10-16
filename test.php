<?php

require __DIR__ . '/vendor/autoload.php';

use KangBabi\PhZipCodes\PhZipCode;

$zipCodes = new PhZipCode();

print_r($zipCodes->getBarangays('region 5', 'albay', 'malilipot'));
print_r($zipCodes->getZipCode(4510));
