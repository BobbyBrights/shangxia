<?php foreach ($blocks as $block): ?>
<?php $type = $block["type"]; $instance = $block["instance"]; $model = $block["model"];?>
  <div class="form-con slideshow-form" ng-controller="ContentForm" ng-init="init()">
    <form name="contentform" class="form-horizontal"  method="post" action="<?php echo Yii::app()->createUrl("page/blocks") ?>">
      <div class="header clearfix">
        <div class="icons">
          <i class="fa fa-edit"></i>
        </div>
        <h4><?php echo Yii::t("strings", "Edit ". str_replace("_", " ",  $instance->key_id))?></h4>
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
          <input type="text" name="title" ng-model="content.title" value="<?php echo htmlspecialchars($instance->title)?>" ng-initial required />
          <p class="text-error" ng-show="content.title.$error.required">This field is required</p>
        </div>
      </div>

      <!-- 内容 Field / 扩展字段 -->
      <?php foreach ($instance->getFields() as $field): ?>
        <?php if ($field == "url_key") continue;?>
        <div class="controle-group">
          <div class="control-label"><label for="<?php echo $field?>"><?php echo Yii::t("fields", ucfirst(str_replace("_", " " ,$field)))?></label></div>
          <div class="controls">
            <?php $option = $instance->getContentFieldOption($field);?>
            <?php if (isset($option["type"]) && $option["type"] == "textarea"): ?>
            <textarea  name="<?php echo $field?>" ng-ckeditor ng-model="content.<?php echo $field?>"  cols="80" rows="10" ng-initial><?php echo ($instance->{$field})?></textarea>
            <?php elseif (isset($option["type"]) && $option["type"] == "select"): ?>
              <select name="<?php echo $field?>" ng-model="content.<?php echo $field?>" value="<?php echo $instance->{$field}?>" ng-initial <?php if (isset($option["select_multi"]) && $option["select_multi"] == TRUE) echo "multiple='multiple'"?>>
                <?php foreach ($option["options"] as $key => $op): ?>
                <option value="<?php echo $key?>"><?php echo $op;?></option>
                <?php endforeach;?>
              </select>
            <?php else: ?>
            <input type="text" value="<?php echo $instance->{$field}?>" name="<?php echo $field?>" ng-model="content.<?php echo $field?>" ng-initial/>
            <?php endif;?>
          </div>
        </div>
      <?php endforeach;?>

      <!-- 图片 Field / 图片扩展字段 -->
      <?php foreach ($instance->getImageFields()  as $field): ?>
      <?php $option = $instance->getImageFieldOption($field);?>
      <div class="control-group imagepreview <?php if ($option["multi"]) echo "multi";?>">
        <div class="control-label"><label for="<?php echo $field?>"><?php echo Yii::t("fields", ucfirst(str_replace("_", " " ,$field)))?></label></div>
        <div class="controls clearfix">
          <ng-uploadimage value='<?php if ($option["multi"]) {echo json_encode($instance->{$field}); } else { echo $instance->{$field} ;}?>' ng-model="content.<?php echo $field?>" <?php if ($option["multi"]) echo "multi";?>>

          </ng-uploadimage>
        </div>
      </div>
      <?php endforeach;?>

      <!-- 视频 Field / 视频扩展字段 -->
      <?php foreach ($model->getVideoFields()  as $field): ?>
      <div class="control-group imagepreview">
        <div class="control-label"><label for="<?php echo $field?>"><?php echo Yii::t("fields", ucfirst(str_replace("_", " " ,$field)))?></label></div>
        <div class="controls clearfix">
          <ng-uploadvideo  ng-model="content.<?php echo $field?>" value="<?php echo $instance->{$field}?>">
            
          </ng-uploadvideo>
        </div>
      </div>
      <?php endforeach;?>
      
      <input type="hidden" name="key_id" value="<?php echo $instance->key_id?>" />
      <input type="hidden" name="type" value="<?php echo $type?>" ng-model="content.type" ng-initial/>
      <input type="hidden" name="cid" value="<?php echo Yii::app()->getRequest()->getParam("id", 0)?>" ng-model="content.cid" ng-initial/>

      <div class="form-actions">
        <div class="controls">
          <input type="submit"  class="btn-primary btn" value="<?php echo Yii::t("strings", "Save")?>"/>
        </div>
     </div>
    </form>
  </div>
<?php endforeach;?>
