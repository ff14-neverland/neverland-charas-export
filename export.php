<?php
require 'vendor/autoload.php';

$api_url = 'http://api.ffneverland.site/wp-json/wp/v2/posts?categories=4';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($curl);
curl_close($curl);

$charas = json_decode($result, true);
foreach($charas as $chara_index => $chara){
  $chara_name = $chara['title']['rendered'];
  $text_count = $chara['custom_fields']['字數'];
  $exp = $chara['custom_fields']['經驗值'];
  $items = $chara['custom_fields']['道具清單'];
  $qq = $chara['custom_fields']['qq號'];
  $header = array(
    '角色名' => 'string',
    '字數' => 'string',
    '經驗值' => 'string',
    '道具清單' => 'string',
    'qq號' => 'string',
  );
  $header_col_options = [
    'font-style' => 'bold',
    'freeze_rows' => 1,
    'freeze_columns' => 1,
  ];
  $rows[] = [
    $chara_name,
    $text_count,
    $exp,
    $items,
    $qq,
  ];
}

$sheet = array(
  'header' => $header,
  'header_col_options' => $header_col_options,
  'rows' => $rows,
);

$sheet_name = 'Chara Information';
$writer = new XLSXWriter();
$writer->writeSheetHeader(
  $sheet_name,
  $sheet['header'],
  $sheet['header_col_options']
);
foreach ($sheet['rows'] as $row) {
  $writer->writeSheetRow($sheet_name, $row);
}
$xlsx_string = $writer->writeToString();
header('Content-disposition: attachment; filename="'. $filename .'"');
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: ' . strlen($xlsx_string));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
exit($xlsx_string);
