<?php

/**
 * Slideshow Content
 */
class SlideShowContentAR extends ContentAR {
  
  public $type = "slideshow";
  
  public function getImageFields() {
    $this->hasImageField("image");
    
    return parent::getImageFields();
  }
  
  public function getFields() {
    $this->hasContentField("link");
    return parent::getFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
}

