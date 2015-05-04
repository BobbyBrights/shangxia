<?php

// 
class UpdateCommand extends CConsoleCommand {
  public function actionUpdate() {
    $file = fopen("./products_en_updates.csv", "r");
    
    $en_products = array();
    // 忽略第一条
    fgetcsv($file);
    while($row = fgetcsv($file)) {
      $en_products[] = $row;
    }
    
    $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
    global $language;
    
    foreach ($en_products as $en_product) {
      $url_key = $en_product[16];
      
      foreach (array("fr") as $lang) {
        $language = $lang;
        $product = ContentAR::loadContentWithUrlKey($url_key, "product");
        if (!$product) {
          print $url_key."\r\n";
        }
        else {
          $product->weight = $en_product[9];
          $product->thumbnail = str_replace("http://http://sxhtml.local/.", "", $en_product[17]);
          $_POST["thumbnail"] = $product->thumbnail;
          
          $product->save();
          
          print ".";
        }
      }
    }
  }
}

