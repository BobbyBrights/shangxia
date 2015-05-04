<?php

class PressCommand extends CConsoleCommand {
  
  public function run($args) {
    return parent::run($args);
  }
  
  public function actionIndex() {
    $presses = require_once("/Users/jackeychen/Workspace/shangxia/docs/press_images/press/export.php");
    global $language;
    $languages = array("en", "fr", "cn");
    foreach ($languages as $lang) {
      $language = $lang;
      foreach ($presses as $press_data) {
        $pressAr = new PressContentAR();
        
        $attributes = array(
            "title" => "LIFE WEEK",
            "body" => "LIFE WEEK",
            "publish_date" => "{$press_data[0]}-{$press_data[1]}",
            "press_image" => "/upload".$press_data[2],
            "master_image" => "/upload". $press_data[2],
            "type" => "press",
        );
        $pressAr->attributes = $attributes;
        $_REQUEST = $attributes;
        $_POST = $attributes;
        
        if ($pressAr->save()) {
          print "Saved.\r\n";
        }
      }
    }
  }
  
  
  
}

