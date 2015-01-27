<?php

/**
 * Press Content
 */
class PressContentAR extends ContentAR {
  public $type = "press";
  
  public static function model($class = __CLASS__) {
    return parent::model($class);
  }
  
  public function getImageFields() {
    $this->hasImageField("thumbnail_small", array("label" => 'Thumbnail (news page)',"multi" => FALSE));
    $this->hasImageField("press_image", array("multi" => FALSE));
    $this->hasImageField("master_image", array("multi" => FALSE));
    return parent::getImageFields();
  }

  public function getVideoFields() {
    $this->hasVideoField('video');

    return parent::getVideoFields();
  }
  
  public function getFields() {
    $this->hasContentField("publish_date");
    return parent::getFields();
  }
  
  public function getList($limit = FALSE, $offset = FALSE) {
    global $language;
    $sql = "SELECT * FROM content LEFT JOIN field on field.cid = content.cid and field.field_name='publish_date' "
            . "WHERE language='".$language."' AND type='".$this->type."'"
            . " ORDER BY field_content DESC";
    if ($limit) {
      $sql .= " LIMIT $limit ";
    }
    
    if ($offset) {
      $sql .= " $offset";
    }
    
    $query = Yii::app()->db->createCommand($sql);
    $rows =  $query->queryAll();
    $cids = array();
    foreach ($rows as $row) {
      $cids[] = $row['cid'];
    }
    $query = new CDbCriteria();
    $query->addInCondition("cid", $cids);
    $tmp_rows = $this->findAll($query);
    $ret_rows = array();
    foreach ($cids as $cid) {
      foreach ($tmp_rows as $tmp_row) {
        if ($tmp_row->cid == $cid) {
          $ret_rows[] = $tmp_row;
        }
      }
    }
    return $ret_rows;
  }
  
  public function getListWithYear($year, $page = 1, $limit = 16) {
    $sql = ' select content.* from content left join field on field.cid = content.cid and field.field_name="publish_date"   where type="press" and (field.field_content) like "%'.$year.'%" ';
    
    $sql .= " ORDER BY field_content ";
    $offset = ($page - 1) * $limit;
    $sql .= " limit $offset, $limit";
    
    $query = Yii::app()->db->createCommand($sql);
    $rows = $query->queryAll();
    
    $cids = array();
    foreach ($rows as $row) {
      $cids[] = $row["cid"];
    }
    
    $query = new CDbCriteria();
    $query->addInCondition("cid", $cids);
    global $language;
    $query->addCondition("language=:language");
    $query->addCondition("type=:type");
    $query->params[":language"] = $language;
    $query->params[":type"] = $this->type;
    
    return $this->findAll($query);
  }
}

