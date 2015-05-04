<?php

class VideoAR extends CActiveRecord {
  public static $format = array(
      "mp4", "webm"
  );
  public function tableName() {
    return "video";
  }
  
  public function primaryKey() {
    return "vid";
  }
  
  public function rules() {
    return array(
        array("name, uri, cid, type, field_name, format, vid", "safe"),
    );
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
  
  /**
   * 判断上传文件是否符合要求
   * @param CUploadedFile $file
   */
  public static function isAllowed($file = NULL) {
    $type = $file->getType();
    $mimes = explode("/", $type);
    if (array_search($mimes[1], self::$format) === FALSE) {
      return FALSE;
    }
    
    return TRUE;
  }
  
  /**
   * 保存上传的媒体文件
   * @param CUploadedFile $file
   */
  public static function saveTo($file) {
    $ext = $file->getExtensionName();
    $uniqueFileName = md5(time(). "_". uniqid()).".". $ext;
    $saveTo = Yii::app()->params["uploadDir"]. "/". $uniqueFileName;
    if ($file->saveAs($saveTo)) {
       $root = dirname(Yii::app()->basePath);
       $uri = str_replace($root, "", $saveTo);
       return $uri;
    }
    else {
      return FALSE;
    }
  }
  
  /**
   * 加载一个对象的Video 数据
   * @param type $obj
   * @param type $field_name
   * @param type $format
   */
  public function loadVideoWithObject($obj, $field_name) {
    $cid = $obj->getPrimaryKey();
    $type = $obj->type;
    $query = new CDbCriteria();
    $query->addCondition("type=:type")
      ->addCondition("field_name=:field_name")
      ->addCondition("cid=:cid");
      //->addCondition("format=:format");
    
    $query->params[":type"] = $type;
    $query->params[":field_name"] = $field_name;
    $query->params[":cid"] = $cid;
    //$query->params[":format"] = $format;
    
    $row = $this->find($query);
    
    return $row;
  }
  
  /**
   * 给一个对象附件视频数据
   * @param CActiveRecord $obj 需要有图片的对象
   */
  public function attachVideoToObject(&$obj, $field_name) {
    $row = $this->loadVideoWithObject($obj, $field_name);
    if ($row) {
      unserialize($row->uri);
      $uri = @unserialize($row->uri);
      
      if (is_string($uri) && $uri != "" ) {
        $obj->{$field_name} = Yii::app()->getBaseUrl(TRUE) .$uri;
      }
      elseif (is_array($uri)) {
        foreach ($uri as &$i) {
          $i = Yii::app()->getBaseUrl(TRUE) .$i;
        }
        $obj->{$field_name} = $uri;
      }
      else {
        $obj->{$field_name} = "";
      }
    }
    else {
      $obj->{$field_name} = "";
    }
  }
  
  /**
   * 给一个对象添加视频字段
   * @param type $video
   * @param type $field
   * @param type $format
   */
  public function saveVideoToObject($obj, $field_name) {
    $request = Yii::app()->getRequest();
    if (!$request->isPostRequest) {
      return;
    }
    $uri = $request->getPost($field_name, false);
    if ($uri === false) {
      return;
    }
    if (is_string($uri)) {
      if (strpos($uri, "http://") !== FALSE) {
        $uri = str_replace(Yii::app()->getBaseUrl(TRUE), "", $uri);
      }

      $filePath = dirname(Yii::app()->basePath)."/". $uri;
      $name = pathinfo($filePath, PATHINFO_FILENAME);
      $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    }
    else {
      foreach ($uri as &$i) {
        $i = str_replace(Yii::app()->getBaseUrl(TRUE), "", $i);
      }

      $filePath = dirname(Yii::app()->basePath)."/". $i;
      $name = pathinfo($filePath, PATHINFO_FILENAME);
      $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    }
    $attr = array(
        "name" => $name,
        "uri" => serialize($uri),
        "cid" => $obj->getPrimaryKey(),
        "type" => $obj->type, 
        "field_name" => $field_name,
        "format" => $ext
    );
    
    // 先查询是否已经存在一份
    $row = $this->loadVideoWithObject($obj, $field_name, $ext);
    if ($row) {
      $row->setAttributes($attr);
      return $row->update();
    }
    else {
      $videoAr = new self();
      $videoAr->setAttributes($attr);
      return $videoAr->save();
    }
  }
}

