<?php

if (isset($_GET["name"])) {
  require_once 'common/inc.php';
  if (ProductContentAR::isType($_GET["name"]) === FALSE) {
    exit(header("Location: /index.php"));
  }
}
else {
  exit(header("Location: /index.php"));
}

$types = ProductContentAR::getType();
$content_title = $types[ProductContentAR::getKeyWithTypeName($_GET["name"])];

$productsGroupWithCollection = getProductInType(ProductContentAR::getKeyWithTypeName($_GET["name"]));
$product_type = $_GET["name"];

if ($_GET['name'] == 'apparel') {
  $timelessCollectionProducts = array();
  $lastCollectionProducts = array();
  $lastCollection = getLastCollection();
  foreach ($productsGroupWithCollection as $collection_id => $products) {
    if ($collection_id == $lastCollection->cid) {
      $lastCollectionProducts = $products;
    }
    else {
      $timelessCollectionProducts = array_merge($timelessCollectionProducts, $products);
    }
  }
}
else {
  $timelessCollectionProducts = array();
  $lastCollectionProducts = array();
  $lastCollection = getLastCollection();
  foreach ($productsGroupWithCollection as $collection_id => $products) {
    if ($collection_id == $lastCollection->cid) {
      $lastCollectionProducts = $products;
    }
    else {
      $timelessCollectionProducts[$collection_id] = $products;
    }
  }
}


?>

  <?php include_once 'common/header.php';?>
		<!-- detail -->
		<div class="section ">
			<div class="detail cs-clear intoview-effect" data-effect="fadeup">
				<div class="arrows arrows2 detailprev" data-a="page-prev" 
          data-link="<?php echo prev_product_type_link($product_type)?>" 
          data-title="<?php echo prev_product_type_title($product_type)?>"></div>
				<div class=" detailcon">
					<h2><?php echo Yii::t("strings", "SHANG XIA ". $_GET["name"])?></h2>
            <div class="Garamond"><?php echo date('Y',time());?></div>
				</div>
				<div class="arrows arrows2 detailnext" data-a="page-next" 
          data-title="<?php echo next_product_type_title($product_type)?>"
          data-link="<?php echo next_product_type_link($product_type)?>"></div>
			</div>
		</div>
    
<!-- Last collection -->
	<!-- <div class="collpiclist cs-clear">
		<div class="collarrows collarrowsprev"></div> -->
		<!--  -->
		<div class="section">
			<div class="products ">

				<!--  -->
				<div class="product-type-wrap">
					<div class="product-type-con">
             <?php $index = 0; ?>
					<ul class="product-type-list">
              <?php foreach ($lastCollectionProducts as $product):?>
              <li class="piclistitem collpicitem intoview-effect <?php if( $index % 3 == 0 ){ echo 'product-type-list-item';} ?>" data-effect="fadeup">
                <a href="<?php echo url("product-detail", array("cid" => $product->cid))?>"><img src="<?php echo makeThumbnail($product->thumbnail, array(412, 390))?>" width="100%" /></a>
                <p><span class="collicon"><?php echo $product->title?></span></p>
              </li>
              <?php 
              $index ++;
              endforeach;?>

                <?php if ($_GET['name'] == 'apparel'): ?>
                    <?php foreach ($timelessCollectionProducts as $product):?>
                        <li class="piclistitem collpicitem intoview-effect <?php if( $index % 3 == 0 ){ echo 'product-type-list-item';} ?>" data-effect="fadeup">
                            <a href="<?php echo url("product-detail", array("cid" => $product->cid))?>"><img src="<?php echo makeThumbnail($product->thumbnail, array(412, 390))?>" width="100%" /></a>
                            <p><span class="collicon"><?php echo $product->title?></span></p>
                        </li>
                    <?php 
                    $index ++;
                    endforeach;?>
                <?php else: ?>
                    <?php foreach ($timelessCollectionProducts as $collection_id => $products): ?>
                        <!--  collection -->
                        <?php $collection = CollectionContentAR::model()->findByPk($collection_id);?>

                        <?php foreach ($products as $product):?>
                            <li class="piclistitem collpicitem intoview-effect <?php if( $index % 3 == 0 ){ echo 'product-type-list-item';} ?>" data-effect="fadeup">
                                <a href="<?php echo url("product-detail", array("cid" => $product->cid))?>"><img src="<?php echo makeThumbnail($product->thumbnail, array(412, 390))?>" width="100%" /></a>
                                <p><span class="collicon"><?php echo $product->title?></span></p>
                            </li>
                        <?php 
                        $index ++;
                        endforeach;?>
                    <?php endforeach;?>
                <?php endif;?>
					</ul>
				</div>
				</div>
                <div class="cs-clear"></div>
			</div>
		</div>
		<!--  -->
		<!-- <div class="collarrows collarrowsnext"></div>
	</div> -->


<?php include_once 'common/footer.php';?>