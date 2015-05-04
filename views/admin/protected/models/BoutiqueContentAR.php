<?php

/**
 * 空间
 */
class BoutiqueContentAR extends ContentAR {
  public $type = "boutique";
  
  public static function getLocation() {
    return array (
        "shanghai" => Yii::t("strings", "Shanghai"),
        "paris" => Yii::t("strings", "Paris"),
        "beijing" => Yii::t("strings", "Beijing")
    );
  }
  
  public function getFields() {
    $this->hasContentField("boutique_title");
    $this->hasContentField("location", array("type" => "select", "options" => self::getLocation()));
    $this->hasContentField("address", array("type" => "textarea"));
    $this->hasContentField("latlng", array("type" => "location"));
    $this->hasContentField("info_title");
    return parent::getFields();
  }
  
  public function getImageFields() {
    $this->hasImageField("boutique_slide", array("multi" => TRUE));
    $this->hasImageField("thumbnail_image", array("multi" => TRUE));
    $this->hasImageField("nav_image");
    return parent::getImageFields();
  }
  
  /**
   * 用地址关键字加载空间
   * @param type $key
   */
  public function loadByAddressKey($key) {
    $list = $this->getList();
    foreach ($list as $item) {
      if ($item->location == $key) {
        return $item;
      }
    }
  }

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }
}

