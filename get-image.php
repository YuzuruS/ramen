<?php
require_once 'phpflickr/phpFlickr.php';

define('API_KEY', '');
define('API_SECRET', '');


download_flicker('塩ラーメン', 'sio');
download_flicker('味噌ラーメン', 'miso');

function download_flicker($keyword, $dir) {
  if (!file_exists($dir)) {
    mkdir($dir);
  }

  $flicker = new phpFlickr(API_KEY, API_SECRET);
  $search_opt = [
    'text' => $keyword,
    'media' => 'photos',
    'license' => '4,5,6,7,8', //商用可能なライセンスを指定
    'per_page' => 200,
    'sort' => 'relevant'
  ];

  $result = $flicker->photos_search($search_opt);
  if (!$result) {
    die("Flicker API error");
  }

  foreach ($result['photo'] as $photo) {
    $farm = $photo['farm'];
    $server = $photo['server'];
    $id = $photo['id'];
    $secret = $photo['secret'];
    $url = "http://farm{$farm}.staticflickr.com/{$server}/{$id}_{$secret}.jpg";
    echo "get $id : $url\n";
    $savepath = "./$dir/$id.jpg";
    if (file_exists($savepath)) {
      continue;
    }
    $bin = @file_get_contents($url);
    if ($bin === false) {
      continue;
    }
    file_put_contents($savepath, $bin);
  }
}
