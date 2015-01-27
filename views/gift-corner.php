<?php 
$pagename='gift-corner';
if (isset($_GET["key"])) {
  require_once 'common/inc.php';
  $crtgift = ContentAR::loadContentWithUrlKey($_GET["key"], "gift");
  if (!$crtgift || $crtgift->type != GiftContentAR::model()->type) {
    exit(header("Location: /index.php"));
  }
}
else {
  exit(header("Location: /index.php"));
}
include_once 'common/header.php';?>

<script type="text/javascript">
  
  var crtgiftId = "<?php echo $crtgift->cid?>";
</script>

		<!-- detail -->
		<div class="section ">
			<div class="detail coll_product cs-clear">
				<div class="range">
			 	</div>	
				<h2 class="intoview-effect" data-effect="fadeup"><?php echo Yii::t("strings", "shang xia gift corner")?></h2>
				<div class=" detailcon js-horizontal-slide intoview-effect" data-split="1" data-effect="fadeup" data-num="1" style="margin:0 auto;float:none;">
					<div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
					<div class="slide-con">
						<ul class="slide-con-inner cs-clear">
							<li style="float:left;">
                <?php echo Yii::t("strings", "gift_corner_desc")?>
							</li>
						</ul>
					</div>
					<div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
				</div>
			</div>
		</div>
		
		<!-- collection list -->
		<div class="section ">
			<div class="colllistbox ">

        <?php $gifts = GiftContentAR::model()->getList();?>
					
            <?php $index = 0;
            foreach ($gifts as $gift): 
            	$index++;
              if( $index % 3 == 1 ): ?>
              <ul class="piclist cs-clear">
              <?php endif;?>
              <li data-cid="<?php echo $gift->cid ?>" class="piclistitem intoview-effect" data-effect="fadeup">
                <img data-a="i-want-to-buy" data-d="" src="<?php echo makeThumbnail($gift->thumbnail, array(414, 219))?>" width="100%" />
                  <p><span class=""><?php echo $gift->title?></span></p>
                  <div class="price">¥ <?php echo $gift->price;?></div>
                  <a href="<?php echo url('gift-corner', array('cid' => $gift->cid))?>" data-a="i-want-to-buy" class="btn transition-wrap"><span class="transition"><?php echo Yii::t("strings", "I Want To Buy")?><br/><br/><?php echo Yii::t("strings", "I Want To Buy")?></span></a>
                  <textarea style="display:none;">
                  <?php echo json_encode(array("name" => $gift->title, 
                      "pics" => $gift->product_slide_image, 
                      "product" => $gift->cid, 
                      "price" => $gift->price,
                      "color" => $gift->color,
                      "material" => $gift->material,
                      "size" => $gift->size,
                      "unit" => $gift->unit,
                      "desc" => $gift->body))?>
                  </textarea>
                </li>
              <?php if( $index % 3 == 0 ): ?>
              </ul>
              <?php endif;?>
            <?php endforeach;?>
            <?php if( count( $gifts ) % 3 != 0 ): ?>
            </ul>
            <?php endif;?>
					
			</div>
		</div>
		<!--  -->
<?php include_once 'common/footer.php';?>
