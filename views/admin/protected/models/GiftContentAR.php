<?php

class GiftContentAR extends ContentAR {
  
  public $type = "gift";
  
  public function getFields() {
    $this->hasContentField("category", array("type" => "select", "options" => array(
        "Home ware" => "Home ware",
        "Travel Set" => "Travel Set",
        "Jewelry" => "Jewelry",
        "Accessories" => "Accessories",
    )));
    $this->hasContentField("color");
    $this->hasContentField("price");
    $this->hasContentField("unit");
    $this->hasContentField("size");
    $this->hasContentField("material");
    $this->hasContentField("item_code");
    return parent::getFields();
  }

  public function getImageFields() {
    $this->hasImageField("product_slide_image", array("multi" => TRUE));
    $this->hasImageField("thumbnail");
    return parent::getImageFields();
  }
  
  public function getVideoFields() {
    return parent::getVideoFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
}

