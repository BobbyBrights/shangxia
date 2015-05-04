<?php

/**
 * Media 控制器
 */
class MediaController extends Controller {
  
  // 创建一个临时媒体文件
  public function actionTemp() {
    $request = Yii::app()->getRequest();
    
    if ($request->isPostRequest) {
      $mediaFile = CUploadedFile::getInstanceByName("media");
      if (MediaAR::isAllowed($mediaFile)) {
        $uri = MediaAR::saveTo($mediaFile);
        if ($uri) {
          return $this->responseJSON(array("uri" => $uri), "success");
        }
        else {
          return $this->responseError("error", ErrorAR::ERROR_UNKNOWN);
        }
      }
      else {
        return $this->responseError("file error", ErrorAR::ERROR_MEDIA_TYPE);
      }
    }
    else {
      $this->responseError("http verb error", ErrorAR::ERROR_HTTP_VERB_ERROR);
    }
  }
  
  public function actionVideoTemp() {
    $request = Yii::app()->getRequest();
    
    if ($request->isPostRequest) {
      $mediaFile = CUploadedFile::getInstanceByName("media");
      if (VideoAR::isAllowed($mediaFile)) {
        $uri = VideoAR::saveTo($mediaFile);
        if ($uri) {
          return $this->responseJSON(array("uri" => $uri), "success");
        }
        else {
          return $this->responseError("error", ErrorAR::ERROR_UNKNOWN);
        }
      }
      else {
        return $this->responseError("file error", ErrorAR::ERROR_MEDIA_TYPE);
      }
    }
    else {
      $this->responseError("http verb error", ErrorAR::ERROR_HTTP_VERB_ERROR);
    }
  }
}

