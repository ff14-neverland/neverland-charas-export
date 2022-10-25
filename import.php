<?php
require 'vendor/autoload.php';

$import_api_url = 'http://api.ffneverland.site/wp-json/neverland/v1/character/update';
$search_api_url = 'http://api.ffneverland.site/wp-json/neverland/v1/character/search';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('charas.xlsx');
$currSheet = $spreadsheet->getSheet(0);

$rows = $currSheet->getHighestRow();
for($i = 1; $i <= $rows; $i++){
  $chara_name = $currSheet->getCell('A' . $i)->getValue();
  $text_count = $currSheet->getCell('B' . $i)->getValue();
  $exp = $currSheet->getCell('C' . $i)->getValue();
  $items = $currSheet->getCell('E' . $i)->getValue();
  $qq = $currSheet->getCell('F' . $i)->getValue();

  if($chara_name && $qq){
    $charas = json_decode(searchChara($search_api_url, $qq), TRUE);
    foreach($charas as $chara){
      $update_content = json_encode([
        'id' => $chara['ID'],
        'textCount' => $text_count,
        'exp' => $exp,
        'items' => $items,
      ]);
      $result = json_decode(updateChara($import_api_url, $update_content, TRUE));
      var_dump($result);
    }
  }
}

function searchChara($search_api_url, $qq){
  $post_content = json_encode(['qq' => $qq]);
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $search_api_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $post_content);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}
function updateChara($import_api_url, $update_content){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $import_api_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $update_content);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  $result = curl_exec($curl);
  curl_close($curl);
  return $result;
}
