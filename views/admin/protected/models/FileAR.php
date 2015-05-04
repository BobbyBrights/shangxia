<?php

// 文件Field
class FileAR extends CActiveRecord {
  public static $caches = array();
  public static $allowTypes = array("octet-stream" ,"pdf", "txt", "doc", "docx", "png", "jpg", "jpeg", "bmp", "gif");
  
  public static function getCache($key) {
    // 把所有的media 载入
    // 然后放入缓存中，后面直接查缓存
    if (!self::$caches) {
      $res = Yii::app()->db->createCommand("SELECT * FROM media")->queryAll();
      foreach ($res as $row) {
        $key = "{$row["cid"]}_{$row["field_name"]}";
        self::$caches[$key] = MediaAR::model()->findByPk($row["mid"]);
      }
    }
    
    $ret = isset(self::$caches[$key]) ? self::$caches[$key] : FALSE;
    return $ret;
  }
  
  public function tableName() {
    return "file";
  }
  
  public function primaryKey() {
    return "fid";
  }
  
  public function rules() {
    return array(
        array("fid,name,uri,cdate,mdate,type,cid, field_name", "safe"),
    );
  }
  
  /**
   * 判断上传文件是否符合要求
   * @param CUploadedFile $file
   */
  public static function isAllowed($file = NULL) {
    if (!$file) {
      return FALSE;
    }
    $type = $file->getType();
    $mimes = explode("/", $type);
    if (array_search($mimes[1], self::$allowTypes) === FALSE) {
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
  
  public function beforeSave() {
    if ($this->isNewRecord) {
      $this->cdate = date("Y-m-d H:i:s");
    }
    $this->mdate = date("Y-m-d H:i:s");
    return TRUE;
  }
  
  /**
   * 给一个对象追加文件
   * @param ContentAR $obj 需要有文件的对象
   */
  public function saveFileToObject($obj, $field_name) {
    if (!$obj->cid) {
      return;
    }
    $request = Yii::app()->getRequest();
    if (!$_POST) {
      return;
    }
    $uri = $request->getPost($field_name);
    if (!$uri) {
      return ;
    }
    
    if (is_string($uri)) {
      if (strpos($uri, "http://") !== FALSE) {
        $uri = str_replace(Yii::app()->getBaseUrl(TRUE), "", $uri);
      }
        else if (strpos($uri, Yii::app()->getBaseUrl()) === 0) {
          $uri = str_replace(Yii::app()->getBaseUrl(), "", $uri);
        }

      $filePath = dirname(Yii::app()->basePath)."/". $uri;
      $name = pathinfo($filePath, PATHINFO_FILENAME);
      $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    }
    else {
      $newUri = array();
      foreach ($uri as $i) {
        if (strpos($i, "http://") !== FALSE) {
          $i = str_replace(Yii::app()->getBaseUrl(TRUE), "", $i);
        }
        else if (strpos($i, Yii::app()->getBaseUrl()) === 0) {
          $i = str_replace(Yii::app()->getBaseUrl(), "", $i);
        }
        $newUri[] = $i;
      }
      $uri = $newUri;

      $filePath = dirname(Yii::app()->basePath)."/". $i;
      $name = pathinfo($filePath, PATHINFO_FILENAME);
      $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    }
    
    $fieldOption = $obj->getFileFieldOptions($field_name);
    
    // 多文件图片这样处理：
    // 删掉之前的文件，然后新增
    if ($fieldOption["multi"]) {
      // 删除
      $rows = $this->loadFileWithObject($obj, $field_name);
      foreach ($rows as $row) {
        $row->delete();
      }
      
      if (!is_array($uri)) {
        $uri = array($uri);
      }
      
      // 添加
      foreach ($uri as $i) {
        $attr = array(
            "name" => $name,
            "uri" => $i,
            "cid" => $obj->cid,
            "type" => $obj->type, 
            "field_name" => $field_name,
        );
        $mediaAr = new MediaAR();
        $mediaAr->setAttributes($attr);
        $mediaAr->save();
      }
    }
    else {
      $attr = array(
          "name" => $name,
          "uri" => ($uri),
          "cid" => $obj->cid,
          "type" => $obj->type, 
          "field_name" => $field_name,
      );

      // 先查询是否已经存在一份
      $row = $this->loadFileWithObject($obj, $field_name);
      if ($row) {
        $row->setAttributes($attr);
        $row->update();
      }
      else {
        $mediaAr = new FileAR();
        $mediaAr->setAttributes($attr);
        $mediaAr->save();
      }
    }
  }
  
  /**
   * 获取媒体对象
   * @param ContentAR  $obj
   * @param type $field_name
   * @return type
   */
  public function loadFileWithObject($obj, $field_name) {
    if (!self::$caches) {
      self::getCache("");
    }
    $cache_key = "{$obj->cid}_$field_name";
    
    // 先判断是不是多值的Media.
    $cid = $obj->getPrimaryKey();
    $type = $obj->type;
    $query = new CDbCriteria();
    $query->addCondition("type=:type")
      ->addCondition("field_name=:field_name")
      ->addCondition("cid=:cid");
    
    $query->order = "weight DESC";
    $query->params[":type"] = $type;
    $query->params[":field_name"] = $field_name;
    $query->params[":cid"] = $cid;
    
    $fieldOption = $obj->getFileFieldOptions($field_name);
    if ($fieldOption['multi']) {
      $cache_key .= "_multi";
      if (self::getCache($cache_key)) {
        $ret = self::getCache($cache_key);
        return $ret;
      }
      $ret = $this->findAll($query);
      self::$caches[$cache_key] = $ret;
      return $ret;
    }
    
    if (self::getCache($cache_key)) {
      return self::getCache($cache_key);
    }
    
    $ret = $this->find($query);
    self::$caches[$cache_key] = $ret;
    
    return $ret;
  }
  
  /**
   * 给一个对象附件图片数据
   * @param ContentAR $obj 需要有图片的对象
   */
  public function attachFileToObject(&$obj, $field_name) {
    $row = $this->loadFileWithObject($obj, $field_name);
    $fieldOption = $obj->getFileFieldOptions($field_name);
    
    if ($row) {
      // 是一组图片
      if ($fieldOption["multi"]) {
        $uri = array();
        foreach ($row as $item) {
          $uri[] = $item->uri;
        }
      }
      else {
        $uri = $row->uri;
      }
      
      // 组合数据
      if (is_string($uri) && $uri != "" ) {
        $obj->{$field_name} = Yii::app()->getBaseUrl(TRUE) .$uri;
      }
      elseif (is_array($uri)) {
        $new_uri = array();
        foreach ($uri as $u) {
          $new_uri[] = Yii::app()->getBaseUrl(TRUE) .$u;
        }
        $obj->{$field_name} = $new_uri;
      }
      else {
        $obj->{$field_name} = "";
      }
    }
    else {
      $obj->{$field_name} = "";
    }
  }
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
}

