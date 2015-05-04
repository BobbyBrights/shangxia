<?php include_once 'common/header.php';?>

    <?php $event = loadFirstEvent();?>
		<!-- newscrumbs -->
		<div class="newscrumbs">
			<p><a data-a="nav-link" href="<?php echo url("news.php")?>"><?php echo Yii::t("strings", "News")?></a> > <?php echo Yii::t("strings", "SHORT NEWS")?></p>
		</div>
    <?php if ($event): ?>
        <!-- related products -->
      <div class="collpiclist cs-clear">
        <div class="collarrows newsarrowsprev" data-a="page-prev"></div>
        <!--  -->
        <div class="section">
          <div class="picinfor cs-clear">
              <div class="picinfortxt">
              	<div class="picinfortxt-inner">
                  <h2><?php echo $event->title?></h2>
                  <h3><?php echo date("Y M d", strtotime($event->date))?></h3>
                  <?php echo $event->body?>
                </div>
                  <div style="margin-right:50px;">
						<a href="#" data-a="show-pop" class="btn transition-wrap"><span class="transition"><?php echo Yii::t("strings", "read more")?><br><br><?php echo Yii::t("strings", "read more")?></span></a>
						<textarea style="display:none;">
							<h2><?php echo $event->title?></h2>
				            <h3 style="text-transform:uppercase;"><?php echo date("Y M d", strtotime($event->date))?></h3>
				            <div class="body">
				              <?php echo $event->body?>
				            </div>
						</textarea>
				  </div>
              </div>
              <div class="picinforpic">
                  <div class="slide">
                    <div class="slidebox cs-clear">
                      <?php foreach ($event->news_slide_image as $slide_image):?>
                        <img class="slideitem" src="<?php echo $slide_image?>" width="100%" />
                      <?php endforeach;?>
                    </div>
                    <ul class="slidetab">
                      <?php foreach ($event->news_slide_image as $index => $slide_image):?>
                        <li class="<?php if ($index == 0) echo "on"?>"></li>
                      <?php endforeach;?>
                    </ul>
                    <!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
                  </div>
              </div>
              <!--  -->
          </div>
        </div>
        <!--  -->
        <div class="collarrows newsarrowsnext" data-a="page-next"></div>
      </div>
    <?php endif;?>
		<!-- older news -->
		<div class="section">
			<div class="products">
				<div class="productstit othercraftit">
					<h2><?php echo Yii::t("strings", "other news")?></h2>
				</div>
        <?php $groupedNews = loadEventsWithYearGroup();?>
				<div class="productscom slide" data-auto="false" style="margin-top: -50px;">
					<div class="newsoldertime">
						<ul class="slidetab">
              <?php $i = 0; foreach ($groupedNews as $year => $news): $i++?>
                <li class="<?php if( $i == count( $groupedNews ) ) echo 'on'; ?>"><?php echo $year?></li>
              <?php endforeach;?>
            </ul>
					</div>
					<!--  -->
					<div class="productslist cs-clear slidebox" style="margin-left:<?php echo -(count( $groupedNews )-1) * 100 . '%'; ?>">
            <?php foreach ($groupedNews as $year => $newslist): ?>
            	  <div data-year="<?php echo $year?>" class="cs-clear slideitem">
	              <?php foreach ($newslist as $news): ?>
	                <a href="#" data-a="show-news" class="prolistitem newsitem" data-id="<?php echo $news->cid?>">
	                  <img src="<?php echo $news->thumbnail?>" width="100%" />
                    <p><?php echo $news->title?><br /></p>
                    <p class="date"><span class="date"><?php echo date("Y M d", strtotime($news->date))?></span></p>
	                  <script type="text/tpl">
                    	<?php if ($news): ?>
				          	<div class="picinfortxt news-picinfortxt">
				          		<div class="picinfortxt-inner">
						            <h2><?php echo $news->title?></h2>
						            <h3 style="text-transform:uppercase;"><?php echo date("Y M d", strtotime($news->date))?></h3>
						            <div class="body">
						              <?php echo $news->body?>
						            </div>
					            </div>
					            <div style="margin-right:50px;">
									<a href="#" data-a="show-pop" class="btn transition-wrap"><span class="transition"><?php echo Yii::t("strings", "read more")?><br><br><?php echo Yii::t("strings", "read more")?></span></a>
									<textarea style="display:none;">
										<h2><?php echo $news->title?></h2>
							            <h3 style="text-transform:uppercase;"><?php echo date("Y M d", strtotime($news->date))?></h3>
							            <div class="body">
							              <?php echo $news->body?>
							            </div>
									</textarea>
								</div>
				            </div>
							<div class="picinforpic">
				            <?php if ($news->news_slide_image):?>
									<div class="slide">
										<div class="slidebox cs-clear">
				              <?php foreach($news->news_slide_image as $image): ?>
				                <img class="slideitem" src="<?php echo $image?>" width="100%" />
				              <?php endforeach;?>
										</div>
				            <?php endif;?>
				            <?php if ($news->news_slide_image):?>
										<ul class="slidetab">
				              <?php foreach($news->news_slide_image as $index => $image): ?>
											<li class="<?php if ($index == 0) echo "on"?>"></li>
				              <?php endforeach;?>
										</ul>
				            <?php endif;?>
									<!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
								</div>
							</div>
			        	<?php endif;?>
                    </script>
	                </a>
	              <?php endforeach;?>
	              </div>
            <?php endforeach;?>
					</div>
					<!--  -->
				</div>
			</div>
		</div>
		<!--  -->
		<div class="section">
			<div class="detail cs-clear">
				<div class="detail-w" style="width:70%;margin:0 auto;">
					<h2 class="intoview-effect" data-effect="fadeup"><?php echo Yii::t("strings", "about shangxia")?></h2>
					<p class="intoview-effect" data-effect="fadeup"><?php echo Yii::t("strings" ,"about_shangxia_desc")?></p>
	 				<a data-a="nav-link" class="btn transition-wrap" href="<?php echo url("about") ?>"><span class="transition"><?php echo Yii::t("strings", "read more")?><br><br><?php echo Yii::t("strings", "read more")?></a>
				
              </div>
			</div>
		</div>
<?php include_once 'common/footer.php';?>


