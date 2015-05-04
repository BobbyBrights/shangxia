<?php

/**
 * Gift 命令行
 */
class GiftCommand extends CConsoleCommand {
  
  public function actionFile() {
    print "File\r\n";
  }
  
  public function actionImport() {
    $gift_root = "/Users/jackeychen/Workspace/shangxia/docs/gift_docs";
    
    $csv = fopen($gift_root."/gift.csv", "r");
    if (!$csv) {
      print "unable open file\r\n";
    }
    
    $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
    $attributes = array();
    $index = 1;
    // 第一行是Title 不需要导入.
    fgetcsv($csv);
    while ($row = fgetcsv($csv))  {
      $attribute = array(
          "category" => $row[0],
          "item_code" => $row[1],
          "title" => $row[2],
          "cn_title" => $row[3],
          "fr_title" => $row[2],
          "thumbnail" => "/upload/". $row[6],
          "product_slide_image" => array("/upload/". $row[6]),
          "color" => $row[8],
          "cn_color"=> $row[9],
          "fr_color" => $row[8],
          "material" => $row[11],
          "cn_material" => $row[12],
          "fr_material" => $row[11],
          "size" => $row[14],
          "unit" => $row[17],
          "cn_unit" => $row[18],
          "fr_unit" => $row[17],
          "price" => $row[20],
          "cn_price" => $row[20],
          "fr_price" => $row[20],
          "body" => $row[24],
          "cn_body" => $row[23],
          "fr_body" => $row[24],
          "url_key" => "gift-".preg_replace("/(^-)|(-$)/i", "", preg_replace('/([\"\s-&\(\)])+/i', "-", strip_tags(strtolower($row[2]))))."-".$index
      );
      
      $index += 1;
      $attributes[] = $attribute;
    }
    
    // 数据分析出来后，开始导入数据
    global $language;
    
    foreach ($attributes as $index => $attribute) {
      $language =  "en";
      $gift = new GiftContentAR();
      $attribute["language"] = $language;
      $attribute["type"] = "gift";
      $attribute["weight"] = $index;
      $gift->setAttributes($attribute);
      
      // 模拟服务器请求数据
      $_POST = $attribute;
      $_REQUEST = $attribute;
      
      // 英文
      if ($gift->save()) {
        print "EN: {$gift->title} import success\r\n";
      }
      else {
        print "EN: {$gift->title} import error\r\n";
      }
      
      //法文
      $language = "fr";
      $attribute["language"] = $language;
      $attribute["color"] = $attribute["fr_color"];
      $attribute["title"] = $attribute["fr_title"];
      $attribute["material"] = $attribute["fr_material"];
      $attribute["price"] = $attribute["fr_price"];
      $attribute["body"] = $attribute["fr_body"];
      $attribute["unit"] = $attribute["fr_unit"];
      
      $gift = new GiftContentAR();
      $gift->setAttributes($attribute);
      
      // 模拟服务器请求数据
      $_POST = $attribute;
      $_REQUEST = $attribute;
      if ($gift->save()) {
        print "FR: {$gift->title} import success\r\n";
      }
      else {
        print "FR: {$gift->title} import success\r\n";
      }
      
      //中文
      $language = "cn";
      $attribute["language"] = $language;
      $attribute["color"] = $attribute["cn_color"];
      $attribute["title"] = $attribute["cn_title"];
      $attribute["material"] = $attribute["cn_material"];
      $attribute["price"] = $attribute["cn_price"];
      $attribute["body"] = $attribute["cn_body"];
      $attribute["unit"] = $attribute["cn_unit"];
      
      $gift = new GiftContentAR();
      $gift->setAttributes($attribute);
      
      // 模拟服务器请求数据
      $_POST = $attribute;
      $_REQUEST = $attribute;
      if ($gift->save()) {
        print "CN: {$gift->title} import success\r\n";
      }
      else {
        print "CN: {$gift->title} import success\r\n";
      }
    }
  }
}

