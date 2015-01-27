<div class="form-con slideshow-form form-add" ng-controller="ContentForm" ng-init="init()">
  <form name="contentform" class="form-horizontal"  method="post" redirect="<?php echo Yii::app()->createUrl("page/content", array("type" => $type))?>">
    <div class="header clearfix">
      <div class="icons">
        <i class="fa fa-edit"></i>
      </div>
      <h4><?php echo Yii::t("strings", "Add ". ucfirst($type))?></h4>
      <div class="toolbar">
        <nav style="padding: 8px;">
          <a href="javascript:;" class="btn btn-default btn-xs full-box">
            <i class="fa fa-expand"></i>
          </a> 
          <a href="javascript:;" class="btn btn-danger btn-xs close-box">
            <i class="fa fa-times"></i>
          </a>
        </nav>
      </div>
    </div>
    
    <!-- 内容区域 / 基本的区域 -->
    <div class="control-group">
      <div class="control-label">
        <label for=""><?php echo Yii::t("strings", "Title")?></label>
      </div>
      <div class="controls">
        <input type="text" name="title" ng-model="content.title"  required />
        <p class="text-error" ng-show="content.title.$error.required">This field is required</p>
      </div>
    </div>
    <div class="control-group">
      <div class="control-label">
        <label for=""><?php echo Yii::t("strings", "Body")?></label>
      </div>
      <div class="controls">
        <textarea  name="body" ng-ckeditor ng-model="content.body"  cols="80" rows="10"></textarea>
      </div>
    </div>
    
    <div class="control-group">
      <div class="control-label">
        <label for=""><?php echo Yii::t("strings", "Weight")?></label>
      </div>
      <div class="controls">
        <input type="text" name="weight" ng-model="content.weight" />
      </div>
    </div>

    <!-- 内容 Field / 扩展字段 -->
    <?php foreach ($model->getFields() as $field): ?>
      <div class="controle-group">
        <div class="control-label"><label for="<?php echo $field?>"><?php echo Yii::t("fields", ucfirst(str_replace("_", " " ,$field)))?></label></div>
        <div class="controls">
          <?php $option = $model->getContentFieldOption($field);?>
          <?php if (isset($option["type"]) && $option["type"] == "textarea"): ?>
            <textarea  name="<?php echo $field?>" ng-ckeditor ng-model="content.<?php echo $field?>"  cols="80" rows="10"></textarea>
          <?php elseif (isset($option["type"]) && $option["type"] == "select"): ?>
            <select name="<?php echo $field?>" ng-model="content.<?php echo $field?>" <?php if (isset($option["select_multi"]) && $option["select_multi"] == TRUE) echo "multiple='multiple'"?>>
              <?php foreach ($option["options"] as $key => $op): ?>
              <option value="<?php echo $key?>"><?php echo $op;?></option>
              <?php endforeach;?>
            </select>
          <?php else: ?>
            <input type="text" name="<?php echo $field?>" ng-model="content.<?php echo $field?>"/>
          <?php endif;?>
        </div>
      </div>
    <?php endforeach;?>

    <!-- 图片 Field / 图片扩展字段 -->
    <?php foreach ($model->getImageFields()  as $field): ?>
    <?php $option = $model->getImageFieldOption($field)?>
    <div class="control-group imagepreview <?php if (!empty($option["multi"])) echo "multi";?>">
      <div class="control-label"><label for="<?php echo $field?>"><?php echo Yii::t("fields", ucfirst(str_replace("_", " " ,$field)))?></label></div>
      <div class="controls clearfix">
        <ng-uploadimage  ng-model="content.<?php echo $field?>" <?php if (!empty($option["multi"])) echo "multi";?>>

        </ng-uploadimage>
      </div>
    </div>
    <?php endforeach;?>
    
    <!-- 视频 Field / 视频扩展字段 -->
    <?php foreach ($model->getVideoFields()  as $field): ?>
    <div class="control-group imagepreview">
      <div class="control-label"><label for="<?php echo $field?>"><?php echo Yii::t("fields", ucfirst(str_replace("_", " " ,$field)))?></label></div>
      <div class="controls clearfix">
        <ng-uploadvideo  ng-model="content.<?php echo $field?>">
          
        </ng-uploadvideo>
      </div>
    </div>
    <?php endforeach;?>
    
    <input type="hidden" name="type" value="<?php echo $type?>" ng-model="content.type" ng-initial/>
    <input type="hidden"  name="cid" value="<?php echo Yii::app()->getRequest()->getParam("id", 0)?>" ng-model="content.cid" ng-initial/>
    
    <div class="form-actions">
      <div class="controls">
        <input type="button" ng-click="submitContent($event)" class="btn-primary btn" value="<?php echo Yii::t("strings", "Save")?>"/>
        <ng-ajax-scroll-up start="<?php echo Yii::t("strings", "uploading")?>" finish="<?php echo Yii::t("strings", "finsihed")?>"></ng-ajax-scroll-up>
      </div>
   </div>
  </form>
</div>