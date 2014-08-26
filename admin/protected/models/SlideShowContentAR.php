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
    return parent::getFields();
  }
  
}

