<?php

require __DIR__ . '/vendor/autoload.php';

use KangBabi\PhZipCodes\PhZipCode;
use KangBabi\PhZipCodes\PhAddress;

function __print($data)
{
  echo '<pre>';
  print_r($data);
  echo '</pre>';
}

$data = PhAddress::data();

__print($data);
