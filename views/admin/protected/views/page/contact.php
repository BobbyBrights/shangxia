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
    <table class="table table-striped">
      <thead>
        <td><?php echo Yii::t("strings", "Title")?></td>
        <td><?php echo Yii::t("strings", "Type")?></td>
        <td><?php echo Yii::t("strings", "Body")?></td>
        <td><?php echo Yii::t("strings", "Date")?></td>
        <td><?php echo Yii::t("strings", "Actions")?></td>
      </thead>
      <tbody>
        <?php foreach ($list as $item): ?>
        <tr>
          <td><?php echo $item->title?></td>
          <td><?php echo ucfirst($type)?></td>
          <td><?php echo strip_tags($item->body)?></td>
          <td><?php echo $item->cdate?></td>
          <td>
            <a ng-click="preview(<?php echo $item->cid?>)" href="javascript:void()"><?php echo Yii::t("strings", "View")?></a>
            &nbsp;|&nbsp;
            <a ng-click='deleteConfirm(<?php echo $item->cid?>)' href="javascript:void()"><?php echo Yii::t("strings", "Delete")?></a>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>

</div>

