<?php

use KangBabi\PhZipCodes\v2\PhAddress;

require __DIR__ . '/vendor/autoload.php';

PhAddress::load();

dd(
  PhAddress::region('REGION 5')->subAddresses()
);
