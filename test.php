<?php

use KangBabi\PhZipCodes\v2\Enums\Address;
use KangBabi\PhZipCodes\v2\PhAddress;

require __DIR__ . '/vendor/autoload.php';

PhAddress::load();

dd(
  // Address::MUNICIPALITY->alternative(),
  PhAddress::region('REGION 5')->subunits()
);
