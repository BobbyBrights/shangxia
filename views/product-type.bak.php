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
				<div class="arrows arrows2 detailprev" data-a="page-prev"></div>
				<div class=" detailcon">
					<h2><?php echo Yii::t("strings", "SHANG XIA ". $_GET["name"])?></h2>
                    <div class="Garamond"><?php echo date('Y',time());?></div>
				</div>
				<div class="arrows arrows2 detailnext" data-a="page-next"></div>
			</div>
		</div>
		<!-- slide -->
		<div class="banner scroll-lowheight intoview-effect" data-effect="fadeup" name="<?php echo $product_type?>">
      <?php if ($product_type == "apparel"): ?>
        <img class="scroll-lowheight-item" src="/photo/collection-apparels.jpg" width="100%" />
      <?php elseif ($product_type == "jewelry"): ?>
        <img class="scroll-lowheight-item" src="/photo/collection-jewelry.jpg" width="100%" />
      <?php elseif ($product_type == "teaware"): ?>
        <img class="scroll-lowheight-item" src="/photo/product_type-teaware.jpg" width="100%" />
      <?php elseif ($product_type == "homeware"): ?>
        <img class="scroll-lowheight-item" src="/photo/product_type-homeware.jpg" width="100%" />
      <?php else : ?>
        <img class="scroll-lowheight-item" src="/photo/product_type-furniture.jpg" width="100%" />
      <?php endif;?>
		</div>
		<!-- barbg -->
		<div class="barbg intoview-effect" data-effect="fadeup" style="margin-bottom: -50px;"></div>
		<!-- apparel -->

    
<!-- Last collection -->
	<!-- <div class="collpiclist cs-clear">
		<div class="collarrows collarrowsprev"></div> -->
		<!--  -->
		<div class="section">
			<div class="products ">
				<div class="productstit collpictit_app intoview-effect" data-effect="fadeup" style="line-height: 230px;">
					<h2><?php echo $lastCollection->title?></h2>
				</div>	
				<!--  -->
				<div class="products-wrap js-horizontal-slide" data-num="3">
					<div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
					<div class="slide-con">
					<ul class="piclist cs-clear slide-con-inner">
              <?php foreach ($lastCollectionProducts as $product):?>
              <li class="piclistitem collpicitem intoview-effect" data-effect="fadeup">
                <a href="<?php echo url("product-detail", array("cid" => $product->cid))?>"><img src="<?php echo makeThumbnail($product->thumbnail, array(412, 390))?>" width="100%" /></a>
                <p><span class="collicon"><?php echo $product->title?></span></p>
              </li>
              <?php endforeach;?>
					</ul>
				</div>
					<div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
				</div>
			</div>
		</div>
		<!--  -->
		<!-- <div class="collarrows collarrowsnext"></div>
	</div> -->

<?php if ($_GET['name'] == 'apparel'): ?>
  <!-- Timeless collection -->    
	<!-- <div class="collpiclist cs-clear">
		<div class="collarrows collarrowsprev"></div> -->
		<!--  -->
		<div class="section">
			<div class="products ">
				<div class="productstit collpictit_app intoview-effect" data-effect="fadeup" style="line-height: 230px;">
					<h2><?php echo Yii::t("strings", "Timeless Collections")?></h2>					
        </div>
				<!--  -->
				<div class="products-wrap js-horizontal-slide" data-num="3">
					<div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
					<div class="slide-con">
					<ul class="piclist cs-clear slide-con-inner">
              <?php foreach ($timelessCollectionProducts as $product):?>
              <li class="piclistitem collpicitem intoview-effect" data-effect="fadeup">
                <a href="<?php echo url("product-detail", array("cid" => $product->cid))?>"><img src="<?php echo makeThumbnail($product->thumbnail, array(412, 390))?>" width="100%" /></a>
                <p><span class="collicon"><?php echo $product->title?></span></p>
              </li>
            <?php endforeach;?>
					</ul>
				</div>
					<div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
				</div>
			</div>
		</div>
		<!--  -->
<!-- 		<div class="collarrows collarrowsnext"></div>
	</div> -->
<?php else: ?>
  <?php foreach ($timelessCollectionProducts as $collection_id => $products): ?>
    <!--  collection -->
    <?php $collection = CollectionContentAR::model()->findByPk($collection_id);?>

    <!-- <div class="collpiclist cs-clear">
      <div class="collarrows collarrowsprev"></div> -->
      <!--  -->
      <div class="section">
        <div class="products ">
          <div class="productstit collpictit_app intoview-effect" data-effect="fadeup" style="line-height: 230px;">
            <h2><?php echo $collection->title?></h2>					
          </div>
          <!--  -->
          <div class="products-wrap js-horizontal-slide" data-num="3">
            <div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
            <div class="slide-con">
            <ul class="piclist cs-clear slide-con-inner">
                <?php foreach ($products as $product):?>
                <li class="piclistitem collpicitem intoview-effect" data-effect="fadeup">
                  <a href="<?php echo url("product-detail", array("cid" => $product->cid))?>"><img src="<?php echo makeThumbnail($product->thumbnail, array(412, 390))?>" width="100%" /></a>
                  <p><span class="collicon"><?php echo $product->title?></span></p>
                </li>
              <?php endforeach;?>
            </ul>
          </div>
            <div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
          </div>
        </div>
      </div>
      <!--  -->
      <!-- <div class="collarrows collarrowsnext"></div>
    </div> -->
  <?php endforeach;?>
<?php endif;?>

<?php include_once 'common/footer.php';?>