<?php 
if (isset($_GET["type"])) {
  require_once 'common/inc.php';
  $crtboutique = BoutiqueContentAR::model()->loadByAddressKey($_GET["type"]);
  if (!$crtboutique || $crtboutique->type != BoutiqueContentAR::model()->type) {
    exit(header("Location: /index.php"));
  }
}
else {
  exit(header("Location: /index.php"));
}
$content_title = $crtboutique->title;

$pagename="boutique-page";
include_once 'common/header.php';?>
		<!-- detail -->
		<div class="section intoview-effect" data-effect="fadeup">
			<div class="detail cs-clear">
				<div class="arrows arrows2 detailprev" data-a="page-prev" data-title="<?php echo boutique_pre_title($crtboutique)?>"></div>
				<div class=" detailcon">
					<h2><?php echo $crtboutique->boutique_title?></h2>
				</div>
				<div class="arrows arrows2 detailnext" data-a="page-next" data-title="<?php echo boutique_next_title($crtboutique)?>"></div>
			</div>
		</div>
		<!-- slide -->
    <?php if ($crtboutique->boutique_slide): ?>
      <div class="slide intoview-effect scroll-lowheight" data-effect="fadeup">
        <div class="slidebox cs-clear scroll-lowheight-item">
          <?php foreach ($crtboutique->boutique_slide as $image): ?>
            <a class="slideitem"><img src="<?php echo $image?>" width="100%" /></a>
          <?php endforeach;?>
        </div>
        <ul class="slidetab cs-clear">
          <?php foreach ($crtboutique->boutique_slide as $index => $image): ?>
            <li class="<?php if ($index == 0) echo "on";?>"></li>
          <?php endforeach;?>
        </ul>
        <!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
      </div>
    <?php endif;?>
		<!-- barbg -->
		<div class="barbg"></div>
		<!-- intro -->
		<div class="section ">
			<div class="intro introparis">
				<h2 class="intoview-effect" data-effect="fadeup"><?php echo $crtboutique->info_title?></h2>
        <p class="intoview-effect" data-effect="fadeup"><?php echo $crtboutique->body?></p>
      </div>
		</div>


		<!-- parislist -->
		<div class="section">
			<div class="productscom js-horizontal-slide" data-num="3">
				<div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
				<!--  -->
				<div class="slide-con">
					<div class="parislist cs-clear slide-con-inner">
			        <?php if ($crtboutique->thumbnail_image): ?>
			          <?php foreach ($crtboutique->thumbnail_image as $image): ?>
			            <a class="parisitem intoview-effect" data-effect="fadeup">
			              <img src="<?php echo makeThumbnail($image, array(413, 301));?>" width="100%" alt="">
			            </a>
			          <?php endforeach;?>
			        <?php endif;?>
					</div>
				</div>
				<div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
				<!--  -->
			</div>
		</div>
		<!-- find us -->
		<div class="section">
			<div class="products findus">
				<div class="productstit intoview-effect" data-effect="fadeup">
					<h2><?php echo Yii::t("strings", "find us")?></h2>
				</div>

				<!-- store -->
				<div class="store cs-clear">
					<div class="storechoose storechoose2 intoview-effect" data-effect="fadeup" style="padding-top:0;">
						<?php echo $crtboutique->address?>
					</div>
					<div class="storemap intoview-effect" style="height:390px;" <?php if ($_GET["type"] == "paris") echo 'data-map-type="google"'?> data-map="<?php echo $crtboutique->latlng?>" data-effect="fadeup">
                        <a href="https://www.google.com.hk/maps/place/SHANG+XIA/@48.852058,2.32882,17z/data=!4m6!1m3!3m2!1s0x47e671d6c8e54a25:0x1d86b6f5059ed48e!2sSHANG+XIA!3m1!1s0x47e671d6c8e54a25:0x1d86b6f5059ed48e?hl=en" target="_blank">
                            <img src="/images/findus.jpg" width="100%" />
                        </a>
					</div>
				</div>
			</div>
		</div>
		<!--  -->
<?php include_once 'common/footer.php';?>