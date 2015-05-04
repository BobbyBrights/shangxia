<?php 
$pagename = 'contact-page';
include_once 'common/header.php';?>
		<!-- detail -->
		<div class="section intoview-effect" data-effect="fadeup">
			<div class="detail cs-clear">
				<a href="/about" class="arrows arrows2 detailprev" data-a="nav-link" 
					data-title="<?php echo Yii::t('strings', 'ABOUT')?>"
					data-link="/about"></a>
				<!-- <div class="arrows arrows2 detailprev" data-a="page-prev"></div> -->
				<div class=" detailcon">
					<h2><?php echo Yii::t("strings", "contact")?></h2>
				</div>
				<!-- <div class="arrows arrows2 detailnext" data-a="page-next"></div> -->
			</div>
		</div>
		<!-- contactpic  -->
		<div class="section contactpic">
			<div class="knowhow">
				<div class="knowhowcom ">
					<div class="knowhowitem cs-clear intoview-effect" data-effect="fadeup">
						<div class="knowhowintro contactintro  knowhowR">
							<div class="cwrap">
                				<?php echo Yii::t("strings", "contact_shanghai")?>
							</div>
						</div>
						<div class="knowhowpic  knowhowL"><img src="/images/contact-sh.jpg" width="100%" /></div>
					</div>
					<div class="knowhowitem cs-clear intoview-effect" data-effect="fadeup">
						<div class="knowhowintro contactintro  knowhowL">
							<div class="cwrap">
                <?php echo Yii::t("strings", "contact_beijing")?>
							</div>
						</div>
						<div class="knowhowpic  knowhowR"><img src="/images/contact-bj.jpg" width="100%" /></div>
					</div>
					<div class="knowhowitem cs-clear intoview-effect" data-effect="fadeup">
						<div class="knowhowintro contactintro  knowhowR">
							<div class="cwrap">
								<?php echo Yii::t("strings", "contact_paris")?>
							</div>
						</div>
						<div class="knowhowpic  knowhowL"><img src="/images/contact-pa.jpg" width="100%" /></div>
					</div>
				</div>
			</div>
		</div>
		<!-- contact from -->
		<div class="section">
			<div class="products contactform">
				<div class="productstit intoview-effect" data-effect="fadeup" style="line-height: 350px;">
					<h2><?php echo Yii::t("strings", "contact_form_title")?></h2>
				</div>
				<!-- store -->
				<div class="conformbox intoview-effect" data-effect="fadeup">
					<form action="/admin/api/content/contact" method="post" >

						<div class="conformtit"><?php echo Yii::t("strings", "NAME")?> <span class="error" id="name-tip"></span></div>
						<input type="text" name="name" data-required="<?php echo Yii::t("strings", "IS REQUIRED")?>" />
						<div class="conformtit"><?php echo Yii::t("strings", "EMAIL")?> <span class="error" id="email-tip"></span></div>
						<input type="text" name="email" data-required="<?php echo Yii::t("strings", "IS REQUIRED")?>"/>
						<div class="conformtit"><?php echo Yii::t("strings", "MESSAGE")?> <span class="error" id="message-tip"></span></div>
						<div style="position:relative;">
							<textarea name="message" data-required="<?php echo Yii::t("strings", "IS REQUIRED")?>" data-max-length="300" data-max-length-tip="<?php echo Yii::t("strings", "over 300 letters")?>" id="" cols="30" rows="15"></textarea>
							<div class="upload">
								<input type="file" name="file" />
							</div>
							<img src="/images/loading.gif" class="loading-gif">
						</div>
						<div class="conformcheck cs-clear" style="margin-top:10px;">
							<label>
								<input type="checkbox" name="poliry" data-required="<?php echo Yii::t("strings", "Please agree the privacy and policy")?>" />
								<span><?php echo Yii::t("strings", "contact_form_footer_tip")?></span>
              				</label>
						</div>
						<div class="error" id="other-error"></div>
						<button data-a="contact-submit" style="margin-top:15px;" class="conformbtn intoview-effect" data-effect="fadeup"><?php echo Yii::t("strings", "SEND")?></button>
					</form>
				</div>
			</div>
		</div>
		<!--  -->
<?php include_once 'common/footer.php';?>



