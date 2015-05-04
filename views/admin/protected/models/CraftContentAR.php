<?php

class CraftContentAR extends ContentAR {
  public $type = "craft";
  public static $products;
  public static $cached_crafts;
  
  public function getImageFields() {
    $this->hasImageField("thumbnail_image");
    //$this->hasImageField("video_poster");
    $this->hasImageField("nav_image");
    
    return parent::getImageFields();
  }
  
  public function getVideoFields() {
    //$this->hasVideoField("craft_video");
    return parent::getVideoFields();
  }
  
  public function getFields() {
    $this->hasContentField("head_title");
    $this->hasContentField("head_body", array("type" => "textarea"));
    $this->hasContentField("craft_title");
    
    $options = array();
    if (!(self::$products)) {
      foreach (ProductContentAR::model()->getList() as $product) {
        $options[$product->cid] = $product->title;
      }
      self::$products = $options;
    }
    else {
      $options = self::$products;
    }
    $this->hasContentField("product", array("type" => "select", "options" => $options,  "select_multi" => TRUE));
    return parent::getFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
  
  public function loadCraftOption() {
    if (self::$cached_crafts) {
      return self::$cached_crafts;
    }
    global $language;
    $query = Yii::app()->db->createCommand("SELECT * FROM content where type='craft' and status=1 and language='".$language."'")->queryAll();
    $crafts = array();
    foreach ($query as $item) {
      $crafts[$item["cid"]] = $item["title"];
    }
    self::$cached_crafts = $crafts;
    
    return $crafts;
  }
}

