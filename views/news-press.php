<?php 
$pagename = 'news-press';
include_once 'common/header.php';?>
		<!-- newscrumbs -->
		<div class="section intoview-effect" data-effect="fadeup" style="opacity: 1; margin-top: 0px;">
			<div class="detail cs-clear">
				<div class="arrows arrows2 detailprev" data-a="page-prev"></div>
				<div class=" detailcon">
					<h2><?php echo Yii::t("strings", "PRESS")?></h2>

					<!-- <a href="#" title="" class="press-lock btn btn-white transition-wrap"><span class="transition">&nbsp;&nbsp;<?php echo Yii::t("strings", "Press access")?><br/><br/>&nbsp;&nbsp;<?php echo Yii::t("strings", "Press access")?></span></a> -->
				</div>
				<div class="arrows arrows2 detailnext" data-a="page-next"></div>
			</div>
		</div>

		<!-- older news -->
		<div class="section">
			<div class="products">
				<div class="productscom slide" data-auto="false">
          <?php $years = loadPressYears();?>
					<div class="newsoldertime intoview-effect" data-effect="fadeup">
						<ul class="slidetab">
            <?php foreach ($years as $index =>  $year): ?>
              <li data-year="<?php echo $year["year"]?>" class="<?php if ($index == count($years)-1) echo "on"?>"><?php echo $year["year"]?></li>
            <?php endforeach;?>
            			</ul>
					</div>

					<!--  -->
					<div class="press-list-warp productslist cs-clear slidebox" style="margin-left:<?php echo -(count( $years )-1) * 100 . '%'; ?>">
						<?php foreach ($years as $index =>  $year): ?>
						<div data-year="<?php echo $year['year']; ?>" class="cs-clear press-list slideitem">
						</div>
						<?php endforeach;?>
					</div>
					<a href="#" style="margin-bottom:150px;" data-a="loadmore-press" title="" class="btn transition-wrap"><span class="transition"><?php echo Yii::t("strings", "Load more")?><br/><br/><?php echo Yii::t("strings", "Load more")?></span></a>
					<!--  -->
				</div>
			</div>
		</div>
		<!--  -->
<?php include_once 'common/footer.php';?>


