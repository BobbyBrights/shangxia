<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class URLCommand extends CConsoleCommand {
  public function run($args) {
    
    return parent::run($args);
  }
  
  public function actionClean() {
    return;
    global $language;
    $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
    $language = "en";
    
    $en_products = ProductContentAR::model()->getList();
    foreach($en_products as $en_product) {
      preg_match("/-$/i", $en_product->url_key, $matches);
      if (count($matches) > 0) {
        // 先查找中文和法文是否有对应的URL KEY
        $language = "cn";
        $cn_product = ContentAR::loadContentWithUrlKey($en_product->url_key, "product");
        if (!$cn_product) {
          print $en_product->url_key." not found Chinese \r\n";
          continue;
        }
        else {
          $url_key = preg_replace("/-$/", "", $en_product->url_key);
          $language = "en";
          if (!ContentAR::loadContentWithUrlKey($url_key, "product")) {
            // 保存中文版本
            $language = "cn";
            $cn_product->url_key = $url_key;
            $cn_product->save();
          }
          else {
            print "$url_key - Boombo!!!\r\n";
          }
        }
        // 再查找法文
        $language = "fr";
        $fr_product = ContentAR::loadContentWithUrlKey($en_product->url_key, "product");
        if (!$fr_product) {
          print $en_product->url_key." not found French \r\n";
        }
        else {
          $url_key = preg_replace("/-$/", "", $en_product->url_key);
          $language = "en";
          if (!ContentAR::loadContentWithUrlKey($url_key, "product")) {
            // 保存法文版本
            print "SAVING It \r\n";
          }
          else {
            print "$url_key - Boombo!!!\r\n";
          }
        }
        
        // 最后保存英文版本
        $url_key = preg_replace("/-$/", "", $en_product->url_key);
        $language = "en";
        $en_product->url_key = $url_key;
        $en_product->save();
      }
    }
  }
  
  public function actionIndex() {
    return;
    global $language;
    $types = array(
        "collection",
        //"product",
        "craft"
    );
    $languages = array("en");
    
//    foreach ($types as $type) {
//      $class = ucfirst($type)."ContentAR";
//      foreach ($languages as $lang) {
//        $language = $lang;
//        
//        if (class_exists($class)) {
//          $contentAr = new $class();
//          $contents = $contentAr->getList();
//          foreach ($contents as $content) {
//            $title = preg_replace('/([\s-&])+/i', "-", strip_tags(strtolower($content->title)));
//            print $content->title ." " .$content->cid." ".$content->type." ".$language."\r\n";
//
//            $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
//            $_REQUEST["url_key"] = strtolower($title);
//            $content->save();
//          }
//        }
//      }
//    }
    
    // Product + English 更新 url key
//    $language = "en";
//    $productAr = ProductContentAR::model()->getList();
//    foreach ($productAr as $product) {
//      $url_key = trim($product->title);
//      $title = preg_replace('/([\s-&\(\)])+/i', "-", strip_tags(strtolower($url_key)));
//      $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
//      $url_key = strtolower($title);
//      
//      // 更新之前要保证 URK_KEY 是唯一的
//      $exitProduct = ProductContentAR::loadContentWithUrlKey($url_key, "product");
//      if ($exitProduct) {
//        print ($exitProduct->language." ". $exitProduct->cid."\r\n");
//      }
//      $index = 1;
//      if ($exitProduct) {
//        while ($exitProduct) {
//          $tmp_url_key = $url_key. "-{$index}";
//          if ($exitProduct = ProductContentAR::loadContentWithUrlKey($tmp_url_key, "product")) {
//            $index += 1;
//          }
//        }
//        $url_key .= "-{$index}";
//      }
//      
//      $_REQUEST["url_key"] = $url_key;
//      $product->save();
//      print $url_key." ".$language."\r\n";
//    }
    
    // Product + Chinese 更新 url key
//    $language = "cn";
//    $productAr = ProductContentAR::model()->getList();
//    foreach ($productAr as $product) {
//      $url_key = trim($product->url_key);
//      $title = preg_replace('/([\s-&\(\)])+/i', "-", strip_tags(strtolower($url_key)));
//      $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
//      $url_key = strtolower($title);
//      
//      // 更新之前要保证 URK_KEY 是唯一的
//      $exitProduct = ProductContentAR::loadContentWithUrlKey($url_key, "product");
//      if ($exitProduct) {
//        print ($exitProduct->language." ". $exitProduct->cid."\r\n");
//      }
//      $index = 1;
//      if ($exitProduct) {
//        while ($exitProduct) {
//          $tmp_url_key = $url_key. "-{$index}";
//          if ($exitProduct = ProductContentAR::loadContentWithUrlKey($tmp_url_key, "product")) {
//            $index += 1;
//          }
//        }
//        $url_key .= "-{$index}";
//      }
//      
//      $_REQUEST["url_key"] = $url_key;
//      $product->save();
//      print $url_key." ".$language."\r\n";
//    }
    
    // Sound of tea
//    $language = "en";
//    $productAr = ProductContentAR::model()->getList();
//    foreach ($productAr as $product) {
//      if ($product->collection != "20331") {
//        continue;
//      }
//      $url_key = $product->title;
//      $title = preg_replace('/([\s-&\(\)])+/i', "-", strip_tags(strtolower($url_key)));
//      $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
//      $url_key = strtolower($title);
//      
//      // 更新之前要保证 URK_KEY 是唯一的
//      $exitProduct = ProductContentAR::loadContentWithUrlKey($url_key, "product");
//      if ($exitProduct) {
//        print ($exitProduct->language." ". $exitProduct->cid."\r\n");
//      }
//      $index = 1;
//      if ($exitProduct) {
//        while ($exitProduct) {
//          $tmp_url_key = $url_key. "-{$index}";
//          if ($exitProduct = ProductContentAR::loadContentWithUrlKey($tmp_url_key, "product")) {
//            $index += 1;
//          }
//        }
//        $url_key .= "-{$index}";
//      }
//      
//      $_REQUEST["url_key"] = $url_key;
//      try {
//        $product->save();
//      } catch (Exception $ex) {
//        
//      }
//      print $url_key." ".$language."\r\n";
//    }
    
    // Product + Chinese 更新 url key
//    $language = "cn";
//    $productAr = ProductContentAR::model()->getList();
//    foreach ($productAr as $product) {
//      if ($product->collection != "20567") {
//        continue;
//      }
//      $url_key = $product->url_key;
//      $title = preg_replace('/([\s-&\(\)])+/i', "-", strip_tags(strtolower($url_key)));
//      $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
//      $url_key = strtolower($title);
//      
//      // 更新之前要保证 URK_KEY 是唯一的
//      $exitProduct = ProductContentAR::loadContentWithUrlKey($url_key, "product");
//      if ($exitProduct) {
//        print ($exitProduct->language." ". $exitProduct->cid."\r\n");
//      }
//      $index = 1;
//      if ($exitProduct) {
//        while ($exitProduct) {
//          $tmp_url_key = $url_key. "-{$index}";
//          if ($exitProduct = ProductContentAR::loadContentWithUrlKey($tmp_url_key, "product")) {
//            $index += 1;
//          }
//        }
//        $url_key .= "-{$index}";
//      }
//      
//      $_REQUEST["url_key"] = $url_key;
//      try {
//        $product->save();
//      } catch (Exception $ex) {
//        
//      }
//      print $url_key." ".$language."\r\n";
//    }
    
     // Product + French 更新 url key
    $language = "fr";
    $productAr = ProductContentAR::model()->getList();
    foreach ($productAr as $product) {
      // sound of tea
//      if ($product->collection != "20773") {
//        continue;
//      }
      if ($product->collection == "20773") {
        continue;
      }
      
      $url_key = trim($product->url_key);
      $title = preg_replace('/([\s-&\(\)])+/i', "-", strip_tags(strtolower($url_key)));
      $_SERVER["SERVER_NAME"] = "http://sxhtml.local/";
      $url_key = strtolower($title);
      
      // 更新之前要保证 URK_KEY 是唯一的
      $exitProduct = ProductContentAR::loadContentWithUrlKey($url_key, "product");
      if ($exitProduct) {
        print ($exitProduct->language." ". $exitProduct->cid."\r\n");
      }
      $index = 1;
      if ($exitProduct) {
        while ($exitProduct) {
          $tmp_url_key = $url_key. "-{$index}";
          if ($exitProduct = ProductContentAR::loadContentWithUrlKey($tmp_url_key, "product")) {
            $index += 1;
          }
        }
        $url_key .= "-{$index}";
      }
      
      $_REQUEST["url_key"] = $url_key;
      try {
        $product->save();
      } catch (Exception $ex) {
        
      }
      print $url_key." ".$language."\r\n";
    }
  }
  
  /**
   * 删除 -
   */
  public function actionRemovestash() {
    $sql = "SELECT * FROM content left join field on field.cid = content.cid and field.field_name='url_key' WHERE type='product' and field_content like '%-'";
    
    $rows = Yii::app()->db->createCommand($sql)->queryAll();
 
    foreach ($rows as $row) {
      print "{$row["cid"]} {$row["type"]}  ".$row['field_content']." \r\n ";
      $field = FieldAR::model()->findByPk($row["fid"]);
      print "{$field->cdate} ";
      $cleaned_uri = preg_replace("/-$/", "", $field->field_content);
      
      $field->field_content = $cleaned_uri;
      
      $field->save();
      
      
    }
  }
}
