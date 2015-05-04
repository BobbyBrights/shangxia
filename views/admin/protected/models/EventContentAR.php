<?php

/**
 * Event Model
 */
class EventContentAR extends ContentAR {
  public $type = "event";
  
  public function getImageFields() {
    $this->hasImageField("news_slide_image", array("multi" => TRUE));
    $this->hasImageField("thumbnail");
    
    return parent::getImageFields();
  }
  
  public function getFields() {
    $this->hasContentField("date", array("type" => "date"));
    return parent::getFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
}

