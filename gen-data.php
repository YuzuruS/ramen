<?php
require_once 'histogram-lib.inc.php';

$ramen_type = [
  "miso" => "1 0",
  "sio" => "0 1",
];

gen_data("", 40);
gen_data("-test", 14);

echo "ok\n";

function gen_data($dir_type, $count) {
  $data = '';
  $types = ['sio', 'miso'];
  $cnt = 0;
  foreach ($types as $type) {
    $type_list = glob("{$type}{$dir_type}/*jpg");
    shuffle($type_list);
    $type_list = array_slice($type_list, 0, $count);
    $cnt += count($type_list);
    $data .= gen_fann_data($type_list, $type);
  }
  $data = "$cnt 64 2\n" . $data;
  file_put_contents("ramen{$dir_type}.dat", $data);
}

function gen_fann_data($list, $type) {
  global $ramen_type;
  $out = $ramen_type[$type];
  $data = '';
  foreach ($list as $f) {
    $his = make_histogram($f);
    $data .= implode(' ', $his) . "\n";
    $data .= $out . "\n";
  }
  return $data;
}
