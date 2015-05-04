<?php

// 邮件订阅
class NewsletterContentAR extends ContentAR {
  public $type = "newsletter";
  
  public function getFields() {
    $this->hasContentField("phone");
    $this->hasContentField("email");
    return parent::getFields();
  }
  
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }
  
  public function getList($limit = FALSE, $offset = FALSE) {
    global $language;
    // key: {type}_{limit}_{offset}
    $key = "{$language}_{$this->type}_{$limit}_{$offset}";
    if (isset(self::$cached_list[$key])) {
      return self::$cached_list[$key];
    }
    $query = new CDbCriteria();
    
    if ($limit) {
      $query->limit = $limit;
    }
    if ($offset) {
      $query->offset = $offset;
    }
    
    $type = $this->type;
    
    if ($type) {
      $query->addCondition("type=:type", $type);
      $query->params[":type"] = $type;
    }
    
    $query->order = "weight DESC, cdate DESC";
    
    $query->addCondition("status=:status");
    $query->params[":status"] = self::STATUS_ENABLE;
    
    $rows = $this->findAll($query);
    
    // 缓存保存好的Get list
    // key: {type}_{limit}_{offset}
    $key = "{$this->type}_{$limit}_{$offset}";
    self::$cached_list[$key] = $rows;
    
    return $rows;
  }
  
}

