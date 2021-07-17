<?php
require 'vendor/autoload.php';

$import_api_url = 'http://api.ffneverland.site/wp-json/neverland/v1/character/update';
$search_api_url = 'http://api.ffneverland.site/wp-json/neverland/v1/character/search';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('charas.xlsx');
$currSheet = $spreadsheet->getSheet(0);

$rows = $currSheet->getHighestRow();
for($i = 2; $i <= $rows; $i++){
  $chara_name = $currSheet->getCell('A' . $i)->getFormattedValue();
  $text_count = $currSheet->getCell('B' . $i)->getFormattedValue();
  $exp = $currSheet->getCell('C' . $i)->getFormattedValue();

  $related_chara_json = searchChara($search_api_url, $chara_name);
  $related_chara = json_decode($related_chara_json);

  if($related_chara){
    $chara_id = $related_chara->ID;
    $update_values = [
      'id' => $chara_id,
      'textCount' => $text_count,
      'exp' => $exp,
    ];
    $update_result = updateChara($import_api_url, $update_values);
  }
}

function searchChara($search_api_url, $chara_name){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $search_api_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, [
    'title' => $chara_name,
  ]);
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}
function updateChara($import_api_url, $update_values){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $import_api_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $update_values);
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}
