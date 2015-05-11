<?php 
$pagename="about-page";
include_once 'common/header.php';?>
		<!-- detail -->
		<div class="section intoview-effect" data-effect="fadeup">
			<div class="detail cs-clear">
				<a href="/news" class="arrows arrows2 detailprev" data-a="nav-link" data-title="<?php echo Yii::t('strings', 'NEWS')?>"></a>
				<!-- <div class="arrows arrows2 detailprev" data-a="page-prev"></div> -->
				<div class=" detailcon">
					<h2><?php echo Yii::t("strings", "about SHANG XIA")?></h2>
				</div>
				<a href="/contact" class="arrows arrows2 detailnext" data-a="nav-link" data-title="<?php echo Yii::t('strings', 'CONTACT')?>"></a>
				<!-- <div class="arrows arrows2 detailnext" data-a="page-next"></div> -->
			</div>
		</div>
		<!-- slide -->
		<div class="slide scroll-lowheight intoview-effect" data-effect="fadeup">
			<div class="slidebox cs-clear scroll-lowheight-item">
				<div class="slideitem"><img src="/images/about_slide_1.jpg" width="100%" /></div>
				<!-- <div class="slideitem"><img src="/images/about_slide_2.jpg" width="100%" /></div>
				<div class="slideitem"><img src="/images/about_slide_3.jpg" width="100%" /></div> -->
			</div>
			<!-- <ul class="slidetab cs-clear">
				<li class="on"></li>
				<li></li>
				<li></li>
			</ul> -->
			<!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
		</div>
		<!-- barbg -->
		<div class="barbg"></div>
		<!-- intro -->
		<a name="bran"></a>
		<div class="section intoview-effect" data-effect="fadeup">
			<div class="intro introparis ">
				<h2 class="aboutintrotit"><?php echo Yii::t("strings", "about_brand_story_title")?></h2>
        <?php echo Yii::t("strings", "about_brand_story_desc")?>
				</div>
		</div>
		<!-- related products -->
		<a name="arts"></a>
		<div class="section">
			<div class="products">
				<div class="productstit abouttit">
					<h2><?php echo Yii::t("strings", "about_brand_founder_title")?></h2>
				</div>
				<div class="productscom">
					<div class="productsinfor cs-clear">
						<div class="proinfortxt aboutinfortxt bg-fef8f4 intoview-effect" data-effect="fadeup">
							<div class="proinfortxt-inner">
							<!-- <h2> </h2> -->
							<p><?php echo Yii::t("strings", "about_brand_founter_desc")?></p>
							</div>
							<a href="#" class="btn transition-wrap" data-a="show-pop"><span class="transition"><?php echo Yii::t("strings", "Read more")?><br><br><?php echo Yii::t("strings", "Read more")?></span></a>
							<textarea style="display:none;"><?php echo Yii::t("strings", "about_brand_founter_desc")?></textarea>
						</div>
						<div class="proinforpic intoview-effect" data-effect="fadeup">
							<!-- <div class="slide"> -->
								<!-- <div class="slidebox cs-clear"> -->
									<img src="/images/about_1.jpg" width="100%" />
								<!-- </div> -->
								<!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
							<!-- </div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- know how -->
		<a name="hert"></a>
		<div class="section">
			<div class="knowhow">
				<div class="productstit about2tit intoview-effect" data-effect="fadeup">
					<h2><?php echo Yii::t("strings", "about_brand_emplee_subject")?></h2>
				</div>
				<div class="knowhowcom ">
					<div class="knowhowitem cs-clear intoview-effect" data-effect="fadeup">
						<div class="knowhowintro  knowhowR">
							<div class="cwrap c_9e927a">
							<?php echo Yii::t("strings", "about_brand_emplee_one")?>
							</div>
						</div>
            <div class="knowhowpic  knowhowL" style="cursor:default"><img src="/images/about_em_1.jpg" width="100%" /></div>
					</div>
					<div class="knowhowitem cs-clear intoview-effect" data-effect="fadeup">
						<div class="knowhowintro  knowhowL">
							<div class="cwrap c_9e927a">
              <?php echo Yii::t("strings" ,"about_brand_emplee_two")?>
              </div>
            </div>
						<div class="knowhowpic  knowhowR" style="cursor:default"><img src="/images/about_em_2.jpg" width="100%" /></div>
					</div>
					<div class="knowhowitem cs-clear intoview-effect" data-effect="fadeup">
						<div class="knowhowintro  knowhowR">
							<div class="cwrap c_9e927a">
              <?php echo Yii::t("strings" ,"about_brand_emplee_third")?>
              </div>
            </div>
						<div class="knowhowpic  knowhowL" style="cursor:default"><img src="/images/about_em_3.jpg" width="100%" /></div>
					</div>
                    <div class="knowhowitem cs-clear intoview-effect" data-effect="fadeup">
                        <div class="knowhowintro  knowhowL">
                            <div class="cwrap c_9e927a">
                                <?php echo Yii::t("strings" ,"about_brand_emplee_four")?>
                            </div>
                        </div>
                        <div class="knowhowpic  knowhowR" style="cursor:default"><img src="/images/about_em_4.jpg" width="100%" /></div>
                    </div>
				</div>
			</div>
		</div>
		<!--  other jobs -->
		<a name="jobs"></a>
<!--		<div class="section" style="display: none;">-->
<!--			<div class="products othercraf">-->
<!--				<div class="productstit jobstit intoview-effect" data-effect="fadeup">-->
<!--					<h2>--><?php //echo Yii::t("strings", "jobs")?><!--</h2>-->
<!--				</div>-->
<!--				<div class="productscom jobscom">-->
<!--					<!--  -->-->
<!--					<div class="productslist cs-clear">-->
<!--              --><?php //foreach (loadJob() as $job): ?>
<!--                <div class="jobsitem intoview-effect" data-effect="fadeup">-->
<!--                    <h3>--><?php //echo $job->title?><!--</h3>-->
<!--                    <a href="#" class="btn transition-wrap" data-a="show-pop"><span class="transition">--><?php //echo Yii::t("strings", "Read more")?><!--<br><br>--><?php //echo Yii::t("strings", "Read more")?><!--</span></a>-->
<!--                    <textarea style="display:none;">-->
<!--                        <div class="popconabout" id="job-popup">-->
<!--                          <h2>--><?php //echo $job->title?><!--</h2>-->
<!--                          --><?php //if (($job->report_to)):?>
<!--                          <div class="popcontxt">-->
<!--                            <p>--><?php //echo Yii::t("strings", "Reports To")?><!--: --><?php //echo $job->report_to?><!--<br />--><?php //if ($job->location):?><!----><?php //echo Yii::t("strings", "Location")?><!--: --><?php //echo $job->location?><!----><?php //endif;?><!--</p>-->
<!--                          </div>-->
<!--                          --><?php //endif;?>
<!--                          --><?php //if ($job->general_role): ?>
<!--      										<h3>--><?php //echo Yii::t("strings", "General Role")?><!--:</h3>-->
<!--                          <div class="popcontxt">-->
<!--                            <p>--><?php //echo $job->general_role?><!--</p>-->
<!--                          </div>-->
<!--                          --><?php //endif;?>
<!--                          <h3>--><?php //echo Yii::t("strings", "Key Responsibilities")?><!--:</h3>-->
<!--                          <div class="popcontxt">-->
<!--                            <p>-->
<!--                              --><?php //echo $job->key_responsibilities?>
<!--                            </p>-->
<!--                          </div>-->
<!--                          <h3>--><?php //echo Yii::t("strings", "Requirements & Capabilities")?><!--:</h3>-->
<!--                          <div class="popcontxt">-->
<!--                            <p> -->
<!--                              --><?php //echo $job->requirements_capabilities?>
<!--                              </p>-->
<!--                          </div>-->
<!--                          <a href="mailto:hr@shang-xia.com" class="btn popaboutbtn transition-wrap" ><span class="transition">--><?php //echo Yii::t("strings", "Apply")?><!--<br><br>--><?php //echo Yii::t("strings", "Apply")?><!--</span></a>-->
<!--                          <div class="popshare cs-clear">-->
<!--                            <a href="#" style="display:none" class="popsharef"></a>-->
<!--                            <a target="_blank" href="http://twitter.com/intent/tweet?url=http://shang-xia.com/about&text=--><?php //echo urlencode('SHANG XIA | JOBS | '.$job->title)?><!--" class="popsharet"></a>-->
<!--                            <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&title=--><?php //echo urlencode('SHANG XIA | JOBS | '.$job->title)?><!--&summary=&source=&url=http://shang-xia.com/about" class="popsharein"></a>-->
<!--                            <a target="_blank" href="http://service.weibo.com/share/share.php?url=http://shang-xia.com/about&appkey=&title=--><?php //echo urlencode('SHANG XIA | JOBS | '.$job->title)?><!--&pic=&ralateUid=&language=" class="popsharewb"></a>-->
<!--                            <a href="#" style="display:none" class="popsharewx"></a>-->
<!--                          </div>-->
<!--                        </div>-->
<!--                    </textarea>-->
<!--                </div>-->
<!--              --><?php //endforeach;?>
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
		<!--  -->
<?php include_once 'common/footer.php';?>	