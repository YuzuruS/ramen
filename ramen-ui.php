<?php
require_once 'histogram-lib.inc.php';
$up_form = <<<EOS
<h3>塩ラーメンと味噌ラーメンの判定</h3>
<div stype="border:1px; solid silver:padding:12px">
  JPEGファイルを選択してください<br>
  <form enctype="multipart/form-data" method="POST">
    <input name="upfile" type="file"><br>
    <input type="submit" value="アップロード">
  </form>
</div>
EOS;
$head = '<html><meta charset="utf-8"><body>';
$foot = '</body></html>';

if (empty($_FILES['upfile']['tmp_name'])) {
  echo $head . $up_form . $foot;
  exit;
}

$upfile = dirname(__FILE__) . '/upfile.jpg';
move_uploaded_file($_FILES['upfile']['tmp_name'], $upfile);
$target = make_histogram($upfile, false);

$ann = fann_create_from_file("ramen.net");
$res = fann_run($ann, $target);
$ramen_type = ["味噌ラーメン", "塩ラーメン"];
echo $head;
echo "<div stype='text-align:center'><h2>アップした写真</h2>";
echo "<img src='upfile.jpg' width='300'><br>";
foreach ($res as $i => $v) {
  $pre = floor(100*$v);
  if ($pre < 0) $pre = 0;
  $type = $ramen_type[$i];
  $fsize = round(2 * $v);
  if ($fsize < 1) $fsize = 1;
  echo "<span style='font-size:{$fsize}em'>{$type}:{$pre}%</span><br>";
}
echo "<p><a href='ramen-ui.php'>他を調べる</a></p></div>";
echo $foot;
