<?php if (!in_array($type, array("contact", "newsletter"))): ?>  
<div class="table-bar">
    <i class="fa fa-plus-square"></i><a href="<?php echo Yii::app()->createUrl("page/addcontent", array("type" => $type))?>"><?php echo Yii::t("strings", ucfirst($type)." Add New")?></a>
</div>
<?php endif;?>

<div class="table-content" ng-controller='ContentTable' ng-init="init()">
  <header>
    <div class="icons">
      <i class="fa fa-table"></i>
    </div>
    <h5><?php echo Yii::t("strings", ucfirst($type)." Table")?></h5>
  </header>
  <?php if($type == "product"): ?>
  <div class="filters clearfix">
    <div class="filter">
      <select name="collection">
        <?php foreach (CollectionContentAR::model()->getList() as $collection): ?>
        <option value="<?php echo $collection->title?>"><?php echo $collection->title?></option>
        <?php endforeach;?>
      </select>
    </div>
    <div class="filter">
      <select name="craft">
        <?php foreach (CraftContentAR::model()->getList() as $craft):?>
        <option value="<?php echo $craft->title?>"><?php echo $craft->title?></option>
        <?php endforeach;?>
      </select>
    </div>
    <div class="filter">
      <select name="type">
        <?php foreach (ProductContentAR::getType() as $key => $label):?>
        <option value="<?php echo ProductContentAR::getTypeKeyName($key)?>"><?php echo $label?></option>
        <?php endforeach;?>
      </select>
    </div>
  </div>
  <?php endif;?>
    <table class="table table-striped tablepager">
      <thead>
        <td><?php echo Yii::t("strings", "Title")?></td>
        <td><?php echo Yii::t("strings", "Type")?></td>
        <td><?php echo Yii::t("strings", "Body")?></td>
        <td><?php echo Yii::t("strings", "Weight")?></td>
        <td><?php echo Yii::t("strings", "Date")?></td>
        <?php if ($type == "product"): ?>
          <td style=""><?php echo Yii::t("strings", "collection")?></td>
          <td style=""><?php echo Yii::t("strings", "craft")?></td>
          <td style=""><?php echo Yii::t("strings", "product type")?></td>
        <?php endif;?>
        <td><?php echo Yii::t("strings", "Actions")?></td>
      </thead>
      <tbody>
        <?php foreach ($list as $item): ?>
        <tr>
          <td><a href="<?php echo Yii::app()->createUrl("page/addcontent", array("type" => $type, "id" => $item->cid))?>"><?php echo $item->title?></a></td>
          <td><?php echo ucfirst($type)?></td>
          <td class="body"><?php echo strip_tags($item->body)?></td>
          <td><?php echo $item->weight?></td>
          <td><?php echo $item->cdate?></td>
          <?php if ($type == "product"): ?>
            <td >
              <?php $collection = CraftContentAR::model()->findByPk($item->collection);?>
              <?php echo ($collection) ?  $collection->title: "none"?>
            </td>
            <td >
              <?php $craft = CraftContentAR::model()->findByPk($item->craft);?>
              <?php  echo $craft ? $craft->title: "none"?>
            </td>
            <td>
              <?php  echo ProductContentAR::getTypeKeyName($item->product_type)?>
            </td>
          <?php endif;?>
          <td>
            <a href="<?php echo Yii::app()->createUrl("page/addcontent", array("type" => $type, "id" => $item->cid))?>"><?php echo Yii::t("strings", "Edit")?></a>
            &nbsp;|&nbsp;
            <a ng-click='deleteConfirm(<?php echo $item->cid?>)' href="#"><?php echo Yii::t("strings", "Delete")?></a>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>

</div>

