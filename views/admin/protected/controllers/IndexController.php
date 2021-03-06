<?php

class IndexController extends Controller
{
  
  public function beforeAction($action) {
    if (!UserAR::isLogin() && $action->id != "login" && $action->id != "error") {
      return $this->redirect(Yii::app()->createUrl("index/login"));
    }
    return parent::beforeAction($action);
  }
  
	public function actionIndex()
	{
		$this->redirect("page/content?type=product");
	}
  
  // 登录 
  public function actionLogin() {
    $request = Yii::app()->getRequest();
    if ($request->isPostRequest) {
      $ret = UserAR::login($request->getPost("user"), $request->getPost("pass"));
      if ($ret) {
        return $this->redirect(array("index"));
      }
      else {
        //TODO::
      }
    }
    $this->layout = "//layouts/login";
    $this->render("login");
  }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
    $error = Yii::app()->errorHandler->error;
    print_r($error);
    //$this->redirect("/404.html");
	}
  
  public function actionLogout() {
    UserAR::logout();
    
    return $this->redirect("index/login");
  }
  
  public function actionTest() {
    $collection = CollectionContentAR::model()->findByPk(20567);
    print $collection->collection_title."<br >";
    //print $collection->thumbnail_image;
  }
}