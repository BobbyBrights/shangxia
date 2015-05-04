<?php  $homepage = 1; $pagename = 'home-page';?>
<?php include_once 'common/header.php';?>

	<!-- intro -->
	<div class="section intoview-effect story_summary" data-effect="fadeup" data-editme-key="home_brand_story_summary">
    <?php editme("home_brand_story_summary")?>
		<div class="intro">
			<p class="introtxt" data-editme-body=".introtxt"> 
        <?php echo Yii::t("strings", "home_brand_story")?>
      </p>
			<a data-a="nav-link" href="<?php echo url('about')?>" title="" class="introbtn transition-wrap"><span class="transition"><?php echo Yii::t("strings" ,"Brand story")?><br/><br/><?php echo Yii::t("strings", "Brand story")?></span></a>
		</div>
	</div>
	<!-- piclist -->
	<div class="section">
    <?php 
      $blocks = array("home_block_first", "home_block_second", "home_block_third");
    ?>
		<ul class="piclist home-piclist cs-clear">
      <?php foreach ($blocks as $blockId): ?>
      <?php 
        $block = ContentAR::model()->loadByKey($blockId);
        $block->hasImageField("thumbnail");
        $block->hasContentField("link_to");
      ?>
			<li class="piclistitem intoview-effect" data-effect="fadeup" data-editme-key="home_middle_slide_one">
        <a data-a="nav-link" href="<?php echo $block->link_to?>">
          <img src="<?php echo $block->thumbnail?>" width="100%" />
          <p><?php echo $block->title?></p>
				</a>
			</li>
      <?php endforeach;?>
		</ul>
	</div>
	<!-- slide -->
	<div class="slide-wrap section">
		<div class="collarrows collarrowsprev home-collarrowsprev" data-a="home-collarrowsprev"></div>
		<div class="slide intoview-effect" data-effect="fadeup" id="homepage-video-slide" data-auto="false" data-slide="absolute" data-resize="1260:560">
			<ul class="slidebox cs-clear">
				<li href="#" class="slideitem" data-tit="<?php echo Yii::t("strings", "Cashmere Felt")?>" data-mp4="/video/Cashmere_Felt_羊绒毡_30s_1.mp4" data-webm="/video/Cashmere_Felt_羊绒毡_30s_1.webm">
					<img src="/photo/video3.jpg" width="100%" />
				</li>
				<li href="#" class="slideitem" data-tit="<?php echo Yii::t("strings", "Zitan")?>" data-mp4="/video/Zitan_紫檀_30s_1.mp4" data-webm="/video/Zitan_紫檀_30s_1.webm">
					<img src="/photo/video1.jpg" width="100%" />
				</li>
				<li href="#" class="slideitem" data-tit="<?php echo Yii::t("strings", "Eggshell Porcelain")?>" data-mp4="/video/Eggshell_Porcelain_薄胎瓷_30s_1.mp4" data-webm="/video/Eggshell_Porcelain_薄胎瓷_30s_1.webm">
					<img src="/photo/video4.jpg" width="100%" />
				</li>
				<li href="#" class="slideitem" data-tit="<?php echo Yii::t("strings", "Bamboo Weaving")?>" data-mp4="/video/Bamboo_Weaving_竹丝扣瓷_30s_1.mp4" data-webm="/video/Bamboo_Weaving_竹丝扣瓷_30s_1.webm">
					<img src="/photo/video2.jpg" width="100%" />
				</li>
			</ul>
			<div class="slidetip-wrap intoview-effect" data-effect="fadeup" data-effect-delay="500" >
				<div class="slidetip">
					<span class="slidetip2-tit"><?php echo Yii::t("strings", "Cashmere Felt")?></span> <br>
<!--					<span class="slidetip2-index">1/4</span> <br>-->
					<a href="#" title="" data-play-text="<?php echo Yii::t("strings", "Watch video")?>" data-pause-text="<?php echo Yii::t("strings", "Pause video")?>" data-a="homepage-watch-video" class="btn btn-white transition-wrap"><i class="transition"><?php echo Yii::t("strings", "Watch video")?><br/><br/><?php echo Yii::t("strings", "Watch video")?></i></a>
				</div>
			</div>
			<ul class="slidetab cs-clear intoview-effect" data-effect="fadeup" data-effect-delay="700" >
				<li class="on"></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
			<!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
		</div>
		<div class="collarrows collarrowsnext home-collarrowsnext" data-a="home-collarrowsnext"></div>
	</div>
	<!-- barbg -->
	<!-- <div class="barbg intoview-effect" data-effect="fadeup"></div>	 -->

<?php include_once 'common/footer.php';?>
