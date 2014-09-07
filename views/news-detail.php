<?php include_once 'common/header.php';?>

    <?php $news = loadFirstNews();?>
		<!-- newscrumbs -->
		<div class="newscrumbs">
			<p><a href="<?php echo "/news.php"?>"><?php echo Yii::t("strings", "News")?></a> > <?php echo Yii::t("strings", "SHORT NEWS")?></p>
		</div>
    <?php if ($news): ?>
        <!-- related products -->
      <div class="collpiclist cs-clear">
        <div class="collarrows newsarrowsprev" data-a="page-prev"></div>
        <!--  -->
        <div class="section">
          <div class="picinfor cs-clear">
              <div class="picinfortxt">
                  <h2><?php echo $news->title?></h2>
                  <h3><?php echo date("Y M d", strtotime($news->date))?></h3>
                  <?php echo $news->body?>
                  </div>
              <div class="picinforpic">
                  <div class="slide">
                    <div class="slidebox cs-clear">
                      <?php foreach ($news->news_slide_image as $slide_image):?>
                        <img class="slideitem" src="<?php echo $slide_image?>" width="100%" />
                      <?php endforeach;?>
                    </div>
                    <ul class="slidetab">
                      <?php foreach ($news->news_slide_image as $index => $slide_image):?>
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
        <?php $groupedNews = loadNewsWithYearGroup()?>
				<div class="productscom">
					<div class="newsoldertime">
            <?php foreach ($groupedNews as $year => $news): ?>
              <a href="javascript:void(0)"><?php echo $year?></a>
            <?php endforeach;?>
					</div>
					<!--  -->
					<div class="productslist cs-clear">
            <?php foreach ($groupedNews as $year => $newslist): ?>
              <?php foreach ($newslist as $news): ?>
                <a href="#" data-a="show-news" class="prolistitem newsitem" data-year="<?php echo $year?>" data-id="<?php echo $news->cid?>">
                  <img src="<?php echo $news->thumbnail?>" width="100%" />
                  <p><?php echo $news->title?><br /><?php echo date("Y M d", strtotime($news->date))?></p>
                  <textarea style="display:none;">
                  		<?php if ($news): ?>
				          	<div class="picinfortxt">
					            <h2><?php echo $news->title?></h2>
					            <h3><?php echo date("Y M d", strtotime($news->date))?></h3>
					            <div class="body">
					              <?php echo $news->body?>
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
                  </textarea>
                </a>
              <?php endforeach;?>
            <?php endforeach;?>
					</div>
					<!--  -->
				</div>
			</div>
		</div>
		<!--  -->
		<div class="section">
			<div class="detail cs-clear">
				<div style="width:70%;margin:0 auto 100px;">
					<h2 class="intoview-effect" data-effect="fadeup"><?php echo Yii::t("strings", "about shangxia")?></h2>
					<p class="intoview-effect" data-effect="fadeup">SHANGXIA is a brand for art of living that promises a unique encounter with the heritage of
 Chinese design and craftsmanship.Chinese renowned designer Jiang Qiong Er established SHANGXIA with a mission to create a 21st century lifestyle founded on the  finest of Chinese design traditions.</p>
	 				<a href="#" class="btn transition-wrap intoview-effect" data-effect="fadeup">
		                <span class="transition"><?php echo Yii::t("strings", "Read more")?>
		                  <br/><br/><?php echo Yii::t("strings", "Read more")?>
		                </span>
	              	</a>
				</div>
			</div>
		</div>
<?php include_once 'common/footer.php';?>


