<?php

/**
 * Slideshow Content
 */
class NewsContentAR extends ContentAR {
  
  public $type = "news";
  
  public function getImageFields() {
    $this->hasImageField("news_slide_image", array("multi" => TRUE));
    $this->hasImageField("news_slide_image_1260x470", array("multi" => FALSE));
    $this->hasImageField("thumbnail");
    
    return parent::getImageFields();
  }
  
  public function getFields() {
    $this->hasContentField("date", array("type" => "date"));
    $this->hasContentField('tpl', array('type' => 'select', 'options' => array('2' => 'Template One', '1' => 'Template Two', '3' => 'Template Three')));
    
    $this->hasContentField('desc_two', array('type' => 'textarea'));
    $this->hasContentField('title_two');
    
    return parent::getFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
  
  public function getList($limit = FALSE, $offset = FALSE) {
    $list = parent::getList($limit, $offset);
    
    return $list;
  }
  
}

