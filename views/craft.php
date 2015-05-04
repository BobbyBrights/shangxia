<?php 

if (isset($_GET["key"])) {
  require_once 'common/inc.php';
  $loadedCraft = ContentAR::loadContentWithUrlKey($_GET["key"], "craft");
  if (!$loadedCraft || $loadedCraft->type != CraftContentAR::model()->type) {
    exit(header("Location: /index.php"));
  }
}
else {
  exit(header("Location: /index.php"));
}

$content_title = $loadedCraft->title;
?>

<?php 
$pagename="craft-page";
include_once 'common/header.php';?>
<!-- detail -->
		<div class="section ">
			<div class="detail coll_product cs-clear">
				<div class="arrows arrows2 detailprev" data-a="page-prev" data-title="<?php echo craft_pre_title($loadedCraft)?>"></div>
				<div class=" detailcon intoview-effect" data-split="1" data-effect="fadeup" data-num="1">
					<h2 class="intoview-effect" data-effect="fadeup"><?php echo $loadedCraft->craft_title?></h2>
					<div class="slide-con">
						<ul class="slide-con-inner cs-clear">
							<li class="j-scrol-bar" onselectstart="return false;">
								<p><?php echo $loadedCraft->body?></p>
							</li>
						</ul>
					</div>
				</div>
				<div class="arrows arrows2 detailnext" data-a="page-next" data-title="<?php echo craft_next_title($loadedCraft)?>"></div>
			</div>
		</div>
		
		<!-- video -->

		<div class="intoview-effect" data-effect="fadeup">
			<!-- barbg -->
      <?php if (is_weaving($loadedCraft->cid)): ?>
        <div class="video" data-resize="1600:560" data-video-render="1" data-mp4="/video/Bamboo_Weaving_竹丝扣瓷_30s_1.mp4" data-webm="Bamboo_Weaving_竹丝扣瓷_30s_1.webm" style="position:relative;overflow:hidden;height:560px;"  >
          <img src="/photo/video2.jpg" width="100%" />
        </div>
      <?php elseif (is_cashmere($loadedCraft->cid)): ?>
        <div class="video" data-resize="1600:560" data-video-render="1" data-mp4="/video/Cashmere_Felt_羊绒毡_30s_1.mp4" data-webm="Cashmere_Felt_羊绒毡_30s_1.webm" style="position:relative;overflow:hidden;height:560px;"  >
          <img src="/photo/video3.jpg" width="100%" />
        </div>
      <?php elseif (is_eggshell($loadedCraft->cid)): ?>
        <div class="video" data-resize="1600:560" data-video-render="1" data-mp4="/video/Eggshell_Porcelain_薄胎瓷_30s_1.mp4" data-webm="Eggshell_Porcelain_薄胎瓷_30s_1.webm" style="position:relative;overflow:hidden;height:560px;"  >
          <img src="/photo/video4.jpg" width="100%" />
        </div>
      <?php else: ?>
        <div class="video" data-resize="1600:560" data-video-render="1" data-mp4="/video/Zitan_紫檀_30s_1.mp4" data-webm="Zitan_紫檀_30s_1.webm" style="position:relative;overflow:hidden;height:560px;"  >
          <img src="/photo/video1.jpg" width="100%" />
        </div>
      <?php ?>
      <?php endif;?>
			<div class="barbg"></div>
		</div>
		
		<!-- detail -->
		<div class="section ">
			<div class="detail detailW6 cs-clear">
					<h2 class="intoview-effect" data-effect="fadeup"><?php echo $loadedCraft->head_title?></h2>
					<p class="intoview-effect" data-effect="fadeup"><?php echo $loadedCraft->head_body?></p>
			</div>
		</div>
		<!-- know how -->
    <?php if (is_weaving($loadedCraft->cid)): ?>
      <?php include_once 'widget/how-weaving.php';?>
    <?php elseif (is_cashmere($loadedCraft->cid)): ?>
      <?php include_once 'widget/how-cashmere_felt.php';?>
    <?php elseif (is_eggshell($loadedCraft->cid)): ?>
      <?php include_once 'widget/how-eggshell_porcelain.php';?>
    <?php else: ?>
      <?php include_once 'widget/how-zitan.php';?>
    <?php ?>
    <?php endif;?>
    <?php $products = loadCraftRelatedProducts($loadedCraft);?>
		<!-- related products -->
    <?php if (count($products) && FALSE) : ?>
		<div class="section">
			<div class="products">
				<div class="productstit intoview-effect" data-effect="fadeup">
					<h2><?php echo Yii::t("strings", "related products")?></h2>
				</div>
				<div class="productscom">
					<div class="productsinfor cs-clear">
            <?php $product = $products[0];?>
						<div class="proinfortxt intoview-effect" data-effect="fadeup">
							<div class="proinfortxt-inner">
								<h2><?php echo $product->title?></h2>
                                <p><?php echo $product->body?></p>
							</div>
							<a href="#" data-a="show-pop" class="btn transition-wrap"><span class="transition"><?php echo Yii::t("strings", "read more")?><br><br><?php echo Yii::t("strings", "read more")?></span></a>
							<textarea style="display:none;">
								<p><?php echo $product->body?></p>
							</textarea>
						</div>
						<div class="proinforpic intoview-effect" data-effect="fadeup">
							<div class="slide">
								<div class="slidebox cs-clear">
                  <?php foreach ($product->product_slide_image as $index => $slide_image): ?>
                  <img <?php if ($index > 3) echo "data-nopreload"?> class="slideitem" src="/images/product_related.jpg" width="100%" />
                  <?php endforeach;?>
								</div>
								<ul class="slidetab">
                  <?php foreach ($product->product_slide_image as $index => $slide_image): ?>
                    <li class="<?php if ($index == 0) echo "on"?>"></li>
                  <?php endforeach;?>
								</ul>
								<!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
							</div>
						</div>
					</div>
					<!--  -->
				</div>
			</div>
		</div>
    <?php endif;?>

    <!-- related products -->
    <?php if (is_weaving($loadedCraft->cid)): ?>
      <?php include_once 'widget/product-w.php';?>
    <?php elseif (is_cashmere($loadedCraft->cid)): ?>
      <?php include_once 'widget/product-cf.php';?>
    <?php elseif (is_eggshell($loadedCraft->cid)): ?>
      <?php include_once 'widget/product-ep.php';?>
    <?php else: ?>
      <?php include_once 'widget/product-zitan.php';?>
    <?php ?>
    <?php endif;?>

		<!--  other crafts -->
		<div class="section">
			<div class="products othercraf">
				<div class="productstit othercraftit intoview-effect" data-effect="fadeup">
					<h2><?php echo Yii::t("strings", "other crafts")?></h2>
				</div>
				<div class="productscom othercrafcom">
					<!--  -->
					<div class="productslist cs-clear">
            <?php foreach(loadOtherCraft($loadedCraft->cid) as $item): ?>
              <a data-a="nav-link" href="<?php echo url("craft", array("cid" => $item->cid))?>" class="prolistitem intoview-effect" data-effect="fadeup">
                <img src="<?php echo $item->thumbnail_image?>" width="100%" />
                <p><?php echo $item->title?></p>
              </a>
            <?php endforeach;?>
					</div>
				</div>
			</div>
		</div>
<?php include_once 'common/footer.php';?>
