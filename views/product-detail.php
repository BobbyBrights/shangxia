<?php 
if (isset($_GET["key"])) {
  require_once 'common/inc.php';
  $product = ContentAR::loadContentWithUrlKey($_GET["key"], "product");
  if (!$product || $product->type != ProductContentAR::model()->type) {
    exit(header("Location: /index.php"));
  }
}
else {
  exit(header("Location: /index.php"));
}

$content_title = $product->title;

$nextProduct = nextProduct($product);
$prevProduct = prevProduct($product);

$pagename = 'product-detail';
?>
<?php include_once 'common/header.php';?>

		<!--  -->
		<!-- newscrumbs -->
		<div class="newscrumbs">
      	<p><?php echo Yii::t("strings", "collections")?>&nbsp;&gt;&nbsp;<?php $collection = loadCollectionFromProduct($product); ?><a data-a="nav-link" href="<?php echo url("product-type", array("name" => ProductContentAR::getTypeKeyName($product->product_type) ))?>"><?php echo loadProductTypeFromProduct($product);?></a>&nbsp;&gt;&nbsp;<?php echo $product->title?> </p>
		</div>
		<!-- detail -->
		<?php if ($product->product_slide_image): ?>
	      <div data-resize="1600:760" class="section product_slide slide">
	        <div class="slidebox cs-clear">
	          <?php foreach (($product->product_slide_image) as $slide_image):?>
	          <div class="slideitem scroll-lowheight">
	          	<img src="<?php echo makeThumbnail($slide_image, array(1500, "auto"))?>" width="100%" class="scroll-lowheight-item" />
	          </div>
	          <?php endforeach;?>
	        </div>
	        <ul class="slidetab cs-clear">
	          <?php foreach (($product->product_slide_image) as $index => $slide_image):?>
	            <li class="<?php if ($index == 0) echo "on"?>"></li>
	          <?php endforeach;?>
	        </ul>
	        <!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
	      </div>
	    <?php endif;?>
		
		<div class="section intoview-effect" data-effect="fadeup">
		  <div class="product-item cs-clear">
		    <div class="product-text">
		      <h2><?php echo $product->title?></h2>
		      <p><?php echo $product->body?></p>
		    </div>
		    <img class="product-image" src="<?php echo $product->slide_image_one?>" />
		  </div>

		  <div class="product-item product-item-right cs-clear">
		    <div class="product-text">
		      <h2><?php echo $product->title_two?></h2>
		      <p><?php echo $product->short_description_two?></p>
		    </div>
		    <img class="product-image" src="<?php echo $product->slide_image_two?>" />
		  </div>

		  <div class="product-item cs-clear">
		    <div class="product-text">
		      <h2><?php echo $product->title_three?></h2>
		      <p><?php echo $product->short_description_three?></p>
		    </div>
		    <img class="product-image" src="<?php echo $product->slide_image_three?>" />
		  </div>

		<?php if ($product->gift): ?>
		  <a href="#" class="btn btn-wantbuy transition-wrap" data-id="<?php echo $product->cid?>" data-d="product=<?php echo $product->cid?>" data-a="i-want-to-buy"><span class="transition"><?php echo Yii::t("strings", "I Want To Buy")?><br/><br/><?php echo Yii::t("strings", "I Want To Buy")?></span></a>
		<?php endif;?>
		</div>

    
			<!-- barbg -->
		<div class="barbg" style="margin-bottom: -50px;"></div>
		
		<!-- collpiclist -->
    <?php if ($product->craft):?>
    <?php $craft = CraftContentAR::model()->findByPk($product->craft);?>
		<div class="section p-craft">
			<div class="knowhow">
				<div class="knowhowtit coll_video">
					<h2><?php echo $craft->craft_title?></h2>
				</div>
				<div class="coll_videocom ">
          <div class="pcb"><?php echo $craft->body?></div>
          <?php if (is_weaving($product->craft)): ?>
            <div class="video" data-video-render="1" data-mp4="/video/Bamboo_Weaving_竹丝扣瓷_30s_1.mp4" data-webm="Bamboo_Weaving_竹丝扣瓷_30s_1.webm" >
              <img src="/photo/video2.jpg" width="100%" />
            </div>
          <?php elseif (is_cashmere($product->craft)): ?>
            <div class="video" data-video-render="1" data-mp4="/video/Cashmere_Felt_羊绒毡_30s_1.mp4" data-webm="Cashmere_Felt_羊绒毡_30s_1.webm"  >
              <img src="/photo/video3.jpg" width="100%" />
            </div>
          <?php elseif (is_eggshell($product->craft)): ?>
            <div class="video"  data-video-render="1" data-mp4="/video/Eggshell_Porcelain_薄胎瓷_30s_1.mp4" data-webm="Eggshell_Porcelain_薄胎瓷_30s_1.webm">
              <img src="/photo/video4.jpg" width="100%" />
            </div>
          <?php elseif (is_zitan($product->craft)): ?>
            <div class="video" data-video-render="1" data-mp4="/video/Zitan_紫檀_30s_1.mp4" data-webm="Zitan_紫檀_30s_1.webm"  >
              <img src="/photo/video1.jpg" width="100%" />
            </div>
          <?php ?>
          <?php endif;?>
				</div>
				<a data-a="nav-link" href="<?php echo url("collections", array('cid' =>$product->collection ))?>" class="btn transition-wrap" ><span class="transition"><?php echo Yii::t("strings", "View more")?><br/><br/><?php echo Yii::t("strings", "View more")?></span></a>
			</div>
		</div>
    <?php endif;?>
		<!-- collpiclist -->


	<div class="collpiclist cs-clear" style="position:relative">
		<div class="section">
			<div class="products ">
				<div class="productstit ">
					<h2><?php echo Yii::t("strings", "similar products")?></h2>
				</div>
				<!--  -->
				<div class="products-wrap js-horizontal-slide intoview-effect" data-effect="fadeup" data-num="3">
					<div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
					<div class="slide-con">
						<ul class="slide-con-inner piclist cs-clear">
							<?php foreach (loadSimilarProducts($product) as $index => $p): ?>
			                <li class="piclistitem collpicitem intoview-effect" data-effect="fadeup">
			                  	<a data-a="nav-link" href="<?php echo url("product-detail", array("cid" => $p->cid)) ?>"><img <?php if ($index > 3) echo "data-nopreload"?> src="<?php echo makeThumbnail($p->thumbnail, array(600, 570))?>" width="100%" />
			                  		<p><span class="collicon"><?php echo $p->title?></span></p>
			                	</a> 
			                </li>
            	<?php endforeach;?>
						</ul>
					</div>
					<div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
				</div>
			</div>
		</div>
	</div>

<?php include_once 'common/footer.php';?>