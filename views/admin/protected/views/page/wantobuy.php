<?php if (!in_array($type, array("contact", "newsletter", "buy"))): ?>  
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
        <td><?php echo Yii::t("strings", "ID")?></td>
        <td><?php echo Yii::t("strings", "Phone")?></td>
        <td><?php echo Yii::t("strings", "Email")?></td>
        <td><?php echo Yii::t("strings", "Product")?></td>
        <td><?php echo Yii::t("strings", "Type")?></td>
        <td><?php echo Yii::t("strings", "Date")?></td>
        <td><?php echo Yii::t("strings", "Actions")?></td>
      </thead>
      <tbody>
        <?php foreach ($list as $item): ?>
        <?php $product = ProductContentAR::model()->findByPk($item->product); ?>
        <?php if (!$product) continue;?>
        <tr>
          <td><?php echo $item->cid?></td>
          <td><?php echo $item->phone?></td>
          <td><?php echo $item->email?></td>
          <td>
          <?php if ($item->product_type == "product"):?>
            <a target="_blank" href="<?php echo Yii::app()->createUrl('page/addcontent', array('type' => 'product', 'id' => $item->product))?>">
              <!-- <img ng-click="viewimage('<?php echo MediaAR::thumbnail($product->product_slide_image[0], array("500", "auto"));?>')" src="<?php echo MediaAR::thumbnail($product->product_slide_image[0], array("500", "auto"))?>" alt="" /> -->
              <img src="<?php echo MediaAR::thumbnail($product->product_slide_image[0], array("500", "auto"))?>" alt="" />
            </a>
          <?php else: ?>
            <a target="_blank" href="<?php echo Yii::app()->createUrl('page/addcontent', array('type' => 'gift', 'id' => $item->product))?>">
              <!-- <img ng-click="viewimage('<?php echo ($product->thumbnail)?>')" src="<?php echo ($product->thumbnail)?>" alt="" /> -->
              <img src="<?php echo ($product->thumbnail)?>" alt="" />
            </a>
         <?php endif;?>
          </td>
          <td>
          <?php if ($item->product_type == "product"):?>
            <?php echo "Product"?>
          <?php else: ?>
            <?php echo "Gift"?>
         <?php endif;?>
          </td>
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

