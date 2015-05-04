<?php

/**
 * 产品对象
 */
class ProductContentAR extends ContentAR {
  
  const TYPE_APPAREL = 1;
  const TYPE_JEWELRY = 2;
  const TYPE_FURNITURE = 3;
  const TYPE_HOMEWARE = 4;
  const TYPE_TEAWARE = 5;
  
  public $type = "product";
  public function getFields() {
    
    $this->hasContentField("product_type", array("type" => "select", "options" => self::getType()));
    
    $collection = CollectionContentAR::model()->getList();
    $options = array();
    foreach ($collection as $item) {
      $options[$item->cid] = $item->title;
    }
    $this->hasContentField("collection", array("type" => "select", "options" => $options));
    
    $crafOptions = CraftContentAR::model()->loadCraftOption();
    $crafOptions = array(0 => "None") + $crafOptions;
    $this->hasContentField("craft", array("type" => "select", "options" => $crafOptions));
    
    $this->hasContentField("gift", array("type" => "select", "options" => array( "0" => Yii::t("strings", "No"), "1" => Yii::t("strings" ,"Yes") )));
    

    $this->hasContentField('title_two');
    $this->hasContentField('short_description_two', array('type' => 'textarea'));

    $this->hasContentField('title_three');
    $this->hasContentField('short_description_three', array('type' => 'textarea'));


    return parent::getFields();
  }
  
  public static function getType() {
    return array(
        self::TYPE_APPAREL =>  Yii::t("strings", "apparel"),
        self::TYPE_JEWELRY => Yii::t("strings", "jewelry"),
        self::TYPE_TEAWARE => Yii::t("strings", "teaware"),
        self::TYPE_HOMEWARE => Yii::t("strings", "homeware") ,
        self::TYPE_FURNITURE => Yii::t("strings", "furniture"),
        );
  }
  
  public static function getKeyWithTypeName($name) {
    $keys = array(
        self::TYPE_APPAREL => "apparel",
        self::TYPE_JEWELRY => "jewelry",
        self::TYPE_TEAWARE => "teaware",
        self::TYPE_HOMEWARE =>  "homeware" ,
        self::TYPE_FURNITURE =>  "furniture",
    );
    return array_search($name, $keys);
  }
  
  public static function getTypeKeyName($id) {
    $keys = array(
        self::TYPE_APPAREL => "apparel",
        self::TYPE_JEWELRY => "jewelry",
        self::TYPE_TEAWARE => "teaware",
        self::TYPE_HOMEWARE =>  "homeware" ,
        self::TYPE_FURNITURE =>  "furniture",
    );
    
    return $keys[$id];
  }
  
  public static function isType($name) {
    $keys = array(
        self::TYPE_APPAREL => "apparel",
        self::TYPE_JEWELRY => "jewelry",
        self::TYPE_TEAWARE => "teaware",
        self::TYPE_HOMEWARE =>  "homeware" ,
        self::TYPE_FURNITURE =>  "furniture",
    );
    if (array_search($name,$keys) !== FALSE) return TRUE;
  }


  public function getImageFields() {
    $this->hasImageField("product_slide_image", array("multi" => TRUE));
    //$this->hasImageField("video_poster");
    $this->hasImageField("thumbnail");

    $this->hasImageField('slide_image_one');
    $this->hasImageField('slide_image_two');
    $this->hasImageField('slide_image_three');

    return parent::getImageFields();
  }
  
  public function getVideoFields() {
    //$this->hasVideoField("product_video");
    return parent::getVideoFields();
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
  
  public function getList($limit = FALSE, $offset = FALSE) {
    return parent::getList($limit, $offset);
  }
}

