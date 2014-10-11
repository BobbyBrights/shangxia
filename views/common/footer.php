<?php 
  $map = 'baidu';
  $city = getCity();
  if ($city == "paris") {
    $map = "google";
  }
?>
<!-- footer -->
		<div class="footer">
			<?php if( !isset($pagename) || $pagename!='gift-corner' ){ ?>
			<!-- location -->
			 <div class="location intoview-effect" data-effect="fadeup">
			 	<div class="range">
			 		<p class="rangetxt">In addition to the range of homeware and accessories</p>
			 		<a href="<?php echo url("gift-corner.php")?>" title="" class="rangebtn transition-wrap"><span class="transition">Enter the gift corner<br/><br/>Enter the gift corner</span></a>
			 	</div>			
			 </div>
			 <?php } ?>
			<!-- store -->
    <?php $store = BoutiqueContentAR::model()->loadByAddressKey($city); ?>
    <?php if ($store): ?>
		<div class="store-wrap">
			<div class="store cs-clear " style="margin:0 50px;">
        <?php 
          $store = BoutiqueContentAR::model()->loadByAddressKey($city);
          // 如果没有找到，拿认为Shanghai 是默认的
          if (!$store) {
            $store = BoutiqueContentAR::model()->loadByAddressKey("shanghai");
          }
          $cities = array_diff(array_keys(BoutiqueContentAR::getLocation()), array($store->location));
          $locations = BoutiqueContentAR::getLocation();
        ?>
        <?php if ($store): ?>
				<div class="storechoose intoview-effect" data-effect="fadeup">
					<h2><?php echo $store->title?></h2>
          <p><?php echo $store->body?></p>
					<ul class="storechooselist cs-clear">
            <?php foreach ($cities as $city): ?>
            <li><a href="<?php echo url("boutique", array("type" => $city))?>"  title="" class="transition-wrap">
                <span class="transition"><?php echo Yii::t("strings", "{city} boutiquue", array("{city}" => ucfirst($locations[$city])))?>
                  <br/><br/><?php echo Yii::t("strings", "{city} boutiquue", array("{city}" => ucfirst($locations[$city])))?>
                </span>
              </a>
            </li>
            <?php endforeach;?>
					</ul>
				</div>
				<div class="storemap intoview-effect" data-effect="fadeup" style="height:400px;position:relative;">
					<div class="storemap-wrap" id="map" data-map-type="<?php echo $map;?>" data-map="<?php echo $store->latlng?>" >
					</div>
	      <?php endif;?>
				</div>
			</div>
			</div>
    <?php endif;?>


			<!-- sitelinks -->
			<div class="footer-bottom">
				<div class="section sitelinks cs-clear">
					<div class="sitelinkitem intoview-effect" data-effect="fadeup">
						<h2><?php echo Yii::t("strings", "COLLECTIONS")?></h2>
            <?php foreach (CollectionContentAR::model()->getList() as $collection): ?>
              <a data-a="nav-link" href="<?php echo url("collections", array("cid" => $collection->cid))?>"><?php echo $collection->title?></a>
            <?php endforeach;?>
					</div>
					<div class="sitelinkitem intoview-effect" data-effect="fadeup">
            <h2><?php echo Yii::t("strings", "CRAFTS")?></h2>
            <?php foreach (CraftContentAR::model()->getList() as $craft): ?>
              <a data-a="nav-link" href="<?php echo url("craft", array("cid" => $craft->cid))?>"><?php echo $craft->title?></a>
            <?php endforeach;?>
					</div>
					<div class="sitelinkitem sitelinkitemS intoview-effect" data-effect="fadeup">
            <h2><?php echo Yii::t("strings", "BOUTIQUES")?></h2>
            <?php foreach (BoutiqueContentAR::getLocation() as $key => $name): ?>
              <a data-a="nav-link" href="<?php echo url("boutique", array("type" => $key))?>" ><?php echo $name?></a>
            <?php endforeach;?>
					</div>
					<div class="sitelinkitem sitelinkitemS intoview-effect" data-effect="fadeup">
						<h2><?php echo Yii::t("strings", "NEWS")?></h2>
						<a data-a="nav-link" style="display:none;" href="<?php echo url("news")?>"></a>
						<a data-a="nav-link" href="<?php echo url("news-detail")?>"><?php echo Yii::t("strings", "news")?></a>
						<a data-a="nav-link" href="<?php echo url("news-event")?>"><?php echo Yii::t("strings", "events")?></a>
						<a data-a="nav-link" href="<?php echo url("news-press")?>"><?php echo Yii::t("strings", "press")?></a>
					</div>
					<div class="sitelinkitem intoview-effect" data-effect="fadeup">
						<h2><?php echo Yii::t("strings", "ABOUT")?></h2>
						<a data-a="nav-link" href="<?php echo url("about")?>#bran"><?php echo Yii::t("strings", "brand story")?></a>
						<a data-a="nav-link" href="<?php echo url("about")?>#arts"><?php echo Yii::t("strings", "artstic director")?></a>
						<a data-a="nav-link" href="<?php echo url("about")?>#hert"><?php echo Yii::t("strings", "hertage & encounter")?></a>
						<a data-a="nav-link" href="<?php echo url("about")?>#jobs"><?php echo Yii::t("strings", "JOIN SHANG XIA")?></a>
					</div>
					<div class="sitelinkitem sitelinkitemXS intoview-effect" data-effect="fadeup">
						<h2><?php echo Yii::t("strings", "CONTACT")?></h2>
						<a href="#" class="ft_wx">&nbsp;</a>
						<a href="http://www.weibo.com/shangxia" target="_blank" class="ft_wb">&nbsp;</a>
						<br>
						<a href="#" style="width: auto;line-height: 1em;height: auto;margin-top: 48px;" data-a="newsletter"><?php echo Yii::t("strings", "subscribe to shang xia newsletter")?></a>
					</div>
				</div>
				<!-- copyright -->
				<div class="copyright">
          <p><a target="_blank" href="http://www.sgs.gov.cn/lz/licenseLink.do?method=licenceView&entyId=20120529122952909"><?php echo Yii::t("strings", "ICP LICENCE")?> 20120529122952909</a> - <a href='privacy-policy' target='_blank'><?php echo Yii::t("strings", "PRIVACY POLICY LEGAL INFORMATION")?></a></p>
				</div>
			</div>
		</div>
		<!--  -->
	</div>

<script type="text/tpl" id="newsletter">
	<h2><?php echo Yii::t("strings", "SUBSCRIBE TO <br/> SHANG XIA NEWSLETTER")?> </h2>
	<div class="popcontxt">
		<p style="margin:5px 0;"><?php echo Yii::t("strings", "To receive updates about SHANGXIA, <br/> please provide the following information")?> :</p>
		<form class="buy-form newsletter-form conformbox" action="/admin/api/content/newsletter" method="post">
			<div class="conformtit"><?php echo Yii::t("strings", "YOUR NAME")?> <span class="error" id="name-tip"></span></div>
			<input type="text" name="name" data-required="<?php echo Yii::t("strings", "IS REQUIRED")?>"/>
			<div class="conformtit"><?php echo Yii::t("strings", "YOUR EMAIL")?> <span class="error" id="email-tip"></span></div>
			<input type="text" name="email" data-required="<?php echo Yii::t("strings", "IS REQUIRED")?>"/>
			<div class="conformtit"><?php echo Yii::t("strings", "YOUR PHONE")?> <span class="error" id="phone-tip"></span></div>
			<input type="text" name="phone" data-required="<?php echo Yii::t("strings", "IS REQUIRED")?>"/>
			<p style="margin:30px -20px 0"><?php echo Yii::t("strings", "SHANGXIA does not rent or sell customer <br/> email addresses to third parties")?> 
			.</p>
			<button class="conformbtn" data-a="newsletter-send"><?php echo Yii::t("strings", "SEND")?></button>
		</form>
		<div class="form-submit-tip"></div>
	</div>
</script>

<script type="text/tpl" id="i_want_to_buy">
	<div class="js-horizontal-slide product-slider" data-num="1" >
		<div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
		<div class="slide-con">
			<ul class="slide-con-inner cs-clear">
			</ul>
		</div>
		<div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
	</div>
	<div class="product-info">
		<div class="product-img-slide-num">1/1</div>
		<div class="product-info-list">
			<h3>#[name]</h3>
			<p><label>Color :</label> #[color]</p>
			<p><label>Material :</label> #[material]</p>
			<p><label>Size :</label> #[size]</p>
			<p><label>Unit :</label> #[unit]</p>
			<br/>
			<p>￥ #[price]</p>
		</div>
		<div class="product-info-desc">#[desc]</div>
	</div>
	<div class="popcontxt popform-wrap">
		<h3><?php echo Yii::t("strings", "You are interested by buying this product?") ?></h3>
		<p><?php echo Yii::t("strings", "Let us contact you back and we will arrange a way to provide you this prodcut")?></p>
		<form class="buy-form conformbox" action="/admin/api/content/wantobuy" method="post">
			<input type="hidden" name="product" value="#[product]"/>
			<div class="conformtit" style="height:40px;"><span class="error" id="name-tip">&nbsp;</span></div>
			<input type="text" placeholder="Name" name="name" data-required="name required"/>
			<div class="conformtit" style="height:40px;"><span class="error" id="email-tip">&nbsp;</span></div>
			<input type="text" placeholder="Email" name="email" data-required="email required"/>
			<div class="conformtit" style="height:40px;"><span class="error" id="phone-tip">&nbsp;</span></div>
			<input type="text" placeholder="Phone" name="phone" data-required="phone required"/>
			<button data-a="contact-me-back" class="btn transition-wrap"><span class="transition"><?php echo Yii::t("strings", "CONTACT ME BACK")?><br/><br/><?php echo Yii::t("strings", "CONTACT ME BACK")?></span></button>
		</form>
		<div class="form-submit-tip"></div>
	</div>
</script>

<!--  -->

<?php
if( $map == 'google' ) {?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false"></script>
<?php }?>
<script type="text/javascript" src="/js/plugin/modernizr-2.5.3.min.js"></script>
<script type="text/javascript" src="/js/sea/sea-debug.js" data-config="/js/config.js"></script>
<script type="text/javascript" src="/js/sea/plugin-shim.js"></script>
<script type="text/javascript" src="/js/lp.core.js"></script>
<script type="text/javascript" src="/js/lp.base.js"></script>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-3700383-6', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>