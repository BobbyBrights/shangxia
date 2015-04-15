<?php

class ContentController extends Controller {
  
  public function actionDelete() {
    $request = Yii::app()->getRequest();
    
    if (!$request->isPostRequest) {
      return $this->responseError("http verb error", ErrorAR::ERROR_HTTP_VERB_ERROR);
    }
    
    $cid = $request->getPost("cid", FALSE);
    
    if ($cid === FALSE) {
      return $this->responseError("miss arguments", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
    }
    
    $content = ContentAR::model()->findByPk($cid);
    if (!$content) {
      return $this->responseError("miss arguments", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
    }
    $content->delete();
    
    return $this->responseJSON(array(), "success");
  }
  
  
  /*
   * 更新内容接口
   */
  public function actionUpdate() {
    $request = Yii::app()->getRequest();
    
    if (!$request->isPostRequest) {
      return $this->responseError("http verb error", ErrorAR::ERROR_HTTP_VERB_ERROR);
    }
    
    // Step1, 加载对应的Model
    $type = $request->getPost("type");
    $key_id = $request->getPost("key_id");
    if (!$type && !$key_id) {
      return $this->responseError("unkonw", ErrorAR::ERROR_UNKNOWN);
    }
    
    if ($type) {
      $class = ucfirst($type)."ContentAR";
      if (!class_exists($class)) {
        return $this->responseError("unkonw", ErrorAR::ERROR_UNKNOWN);
      }

      // Step2, 然后保存数据
      $model = new $class();
      $model->attributes = $_POST;

      // 是编辑还是添加?
      if (($id = $request->getPost("cid"))) {
        $instance = $model->findByPk($id);
        $instance->attributes = $_POST;
        if ($instance->update()) {
          $this->responseJSON($model->attributes, "success");
        }
        else {
          $this->responseError("save error", ErrorAR::ERROR_INVITE, $instance->getErrors());
        }
      }
      else {
        if ($model->save()) {
          $this->responseJSON($model->attributes, "success");
        }
        else {
          $this->responseError("save error", ErrorAR::ERROR_INVITE, $model->getErrors());
        }
      }
    }
    elseif ($key_id) {
      $content = ContentAR::model()->loadByKey($key_id);
    }
  }
  
  
  public function actionTest() {
    PressContentAR::model()->getList();
 }
 
 public function actionContact() {
   $request = Yii::app()->getRequest();
   
   if (!$request->isPostRequest) {
     return $this->responseError("invalid http verb", ErrorAR::ERROR_HTTP_VERB_ERROR);
   }
   
   //name , email, message, 
   $name = $request->getPost("name");
   $email = $request->getPost("email");
   $message = htmlspecialchars($request->getPost("message"));
   
   $fileName = "file";
   $file = CUploadedFile::getInstanceByName($fileName);
   if ($file && !FileAR::isAllowed($file)) {
     return $this->responseError(Yii::t("strings", "Uploaded media is denied by server"), ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   if ($file && $file->getSize() > 1024 * 1024) {
     return $this->responseError(Yii::t("strings", "Uploaded media is denied by server"), ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   if ($file) {
    // Make temp file
    $mediaAr = new MediaAR();
    $uri = $mediaAr->saveTo($file);
    $_POST[$fileName] = $uri;

    if (!$uri) {
      return $this->responseError(Yii::t("There's error in server interval. please try later"), ErrorAR::ERROR_UNKNOWN);
    }
   }
   
   if (!$name || !$email) {
     return $this->responseError("There's error in server interval. please try later", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     return $this->responseError("There's error in server interval. please try later", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $contactAr = ContactContentAR::model();
   $contactAr->attributes = array(
       "title" => $name,
       "email" => $email,
       "body" => $message
   );
   $contactAr->setIsNewRecord(true);
   
   if ($ret = $contactAr->save()) {
     return $this->responseJSON(array(), Yii::t("strings" ,"Your message has been successfully sent. We will contact you soon.

<br />Shang Xia"));
   }
   else {
     $error = $contactAr->getErrors();
     return $this->responseError("There's error in server interval. please try later", ErrorAR::ERROR_UNKNOWN, $error);
   }
 }
 
 public function actionNewsletter() {
   $request = Yii::app()->getRequest();
   
   if (!$request->isPostRequest) {
     return $this->responseError("invalid http verb", ErrorAR::ERROR_HTTP_VERB_ERROR);
   }
   
   //name , email, message, 
   $name = $request->getPost("name");
   $email = $request->getPost("email");
   $phone = $request->getPost("phone");
   
   if (!$name || !$email) {
     return $this->responseError("invild params error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     return $this->responseError("email error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $newsletterAr = NewsletterContentAR::model();
   $newsletterAr->attributes = array(
       "title" => $name,
       "phone" => $phone,
       "email" => $email
   );
   $newsletterAr->setIsNewRecord(true);
   
   if ($ret = $newsletterAr->save()) {
     return $this->responseJSON(array(), Yii::t("strings" , "Your message has been successfully sent. We will contact you soon.

<br />Shang Xia"));
   }
   else {
     $error = $newsletterAr->getErrors();
     return $this->responseError("post message error", ErrorAR::ERROR_UNKNOWN, $error);
   }
 }
 
 public function actionIndex() {
   $request = Yii::app()->getRequest();
   
   $cid = $request->getParam("cid");
   if (!$cid) {
     return $this->responseError("invild params error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $contact = ContactContentAR::model()->findByPk($cid);
   
   if (!$contact) {
     return $this->responseError("invild params error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $this->responseJSON($contact->getAttributes(), Yii::t("strings" , "Your message has been successfully sent. We will contact you soon.

<br />Shang Xia"));
 }
 
 public function actionNews() {
   $request = Yii::app()->getRequest();
   
   $cid = $request->getParam("id");
   if (!$cid) {
     return $this->responseError("invild params error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $news = NewsContentAR::model()->findByPk($cid);
   
   if (!$news) {
     return $this->responseError("invild params error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $this->responseJSON($news, "success");
 }
 
 public function actionWantobuy() {
   $request = Yii::app()->getRequest();
   
   if (!$request->isPostRequest) {
     return $this->responseError("invalid http verb", ErrorAR::ERROR_HTTP_VERB_ERROR);
   }
   
   //name , email, message, 
   $name = $request->getPost("name");
   $email = $request->getPost("email");
   $phone = $request->getPost("phone");
   $product_id = $request->getPost("product");
   
   if (!$name || !$email || !$product_id) {
     return $this->responseError("invild params error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     return $this->responseError("email error", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $product = ProductContentAR::model()->findByPk($product_id);
   if (!$product) {
     return $this->responseError("product is not exist", ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   
   $buyAr = BuyContentAR::model();
   $buyAr->attributes = array(
       "title" => $name,
       "phone" => $phone,
       "email" => $email,
       "product" => $product->cid
   );
   $buyAr->setIsNewRecord(true);
   
   if ($buyAr->save()) {
     return $this->responseJSON(array(), Yii::t("strings" , "Your message has been successfully sent. We will contact you soon. Shang Xia"));
   }
   else {
     $error = $buyAr->getErrors();
     return $this->responseError("post message error", ErrorAR::ERROR_UNKNOWN, $error);
   }
 }
 
 // 用年份 查询Press
 public function actionPress() {
   $request = Yii::app()->getRequest();
   
   $year = $request->getParam("year", FALSE);
   $page = $request->getParam("page", 1);
   if (!$year) {
     return $this->responseError('miss param error', ErrorAR::ERROR_MISSED_REQUIRED_PARAMS);
   }
   $presses = PressContentAR::model()->getListWithYear($year, $page);
   
   $newPresses = array();
   foreach ($presses as $press) {
     $attributes = $press->getAttributes();
     $attributes['press_image'] = MediaAR::thumbnail($press->press_image, array(355, 475));
     $newPresses[] = $attributes;
   }
   return $this->responseJSON($newPresses, "success");
 }
}

