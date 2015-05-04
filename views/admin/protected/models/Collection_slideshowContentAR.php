<?php

class Collection_slideshowContentAR extends ContentAR {
  
  public $type = "collection_slideshow";
  
  public function getImageFields() {
    $this->hasImageField("image");
    
    return parent::getImageFields();
  }
  
  public function getFields() {
    return parent::getFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
}
