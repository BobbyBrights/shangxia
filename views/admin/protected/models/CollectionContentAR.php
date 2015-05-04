<?php

/**
 * Collection 内容类型
 */
class CollectionContentAR extends ContentAR {
  public $type = "collection";
  
  public function getImageFields() {
    $this->hasImageField("slide_image", array("multi" => TRUE));
    $this->hasImageField("thumbnail_image");
    $this->hasImageField("nav_image");
    return parent::getImageFields();
  }
  
  public function getFields() {
    $this->hasContentField("public_date");
    $this->hasContentField("collection_title");
    return parent::getFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
}

