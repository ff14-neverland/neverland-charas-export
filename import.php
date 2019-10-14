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
}

function searchChara($search_api_url){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $api_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($curl);
  curl_close($curl);
}
function updateChara($import_api_url){
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $api_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($curl);
  curl_close($curl);
}
