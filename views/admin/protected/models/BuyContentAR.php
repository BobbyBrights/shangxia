<?php

class BuyContentAR extends ContentAR {
  public $type = "buy";
  
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }
  
  public function getFields() {
    $this->hasContentField("phone");
    $this->hasContentField("email");
    $this->hasContentField("product");
    $this->hasContentField("product_type");
    return parent::getFields();
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

  public function afterSave() {
    parent::afterSave();
    $product = ProductContentAR::model()->findByPk($this->product);
    $mail = Yii::app()->Smtpmail;
    if ($this->email && is_array($this->email)) {
      $to = $this->email[0];
    }
    else {
      $to = $this->email;
    }

    $username = $this->title;
    $phone = $this->phone;
    if ($this->phone && is_array($this->phone)) {
      $phone = $this->phone[0];
    }
    else {
      $phone = $this->phone;
    }

    $productType = $this->product_type;
    if (is_array($productType)) {
      $productType = $productType[0];
    }
    else {
      $productType = 'product';
    }

    $pname = $product->title;

    if ($productType == 'gift') {
      $plink = Yii::app()->createAbsoluteUrl('page/addcontent', array('type' => 'gift', 'id' => $product->cid));
    }
    else {
      $plink = Yii::app()->createAbsoluteUrl('page/addcontent', array('type' => 'product', 'id' => $product->cid));
    }


    $mail->SetFrom('jackey@berule.com');
    $mail->AddAddress($to, "");
    $mail->Subject = "Want to buy";
    $html = "<h4> Who want to buy</h5> <br /> name: {$username} <br \> phone: {$phone} <br \> type: {$productType} <br \> Product: {$pname} <br /> Link: {$plink} <br />";
    $mail->MsgHTML($html);
    $ret = $mail->Send();
  }
}

