<?php

class JobContentAR extends ContentAR {
  
  public $type = 'job';
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
  
  public function getFields() {
    $this->hasContentField("location");
    $this->hasContentField("report_to");
    $this->hasContentField("responsible_for");
    $this->hasContentField("general_role", array("type" => "textarea"));
    $this->hasContentField("key_responsibilities", array("type" => "textarea"));
    $this->hasContentField("requirements_capabilities", array("type" => "textarea"));
    
    return parent::getFields();
  }
}

