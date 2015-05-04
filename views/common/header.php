<?php 
require_once "inc.php";?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php if (isset($content_title)) echo $content_title." | "?><?php echo $page_title?> | <?php echo ucwords(Yii::t("strings" ,"shang xia"))?></title>
  <meta name="viewport" content="minimal-ui, width=640, minimum-scale=0.5, maximum-scale=0.5, target-densityDpi=290,user-scalable=no" />
  <meta name="keywords" content="<?php echo Yii::t("page_title", "meta_keywords")?>" />
  <meta name="description" content="<?php echo Yii::t("page_title", "meta_desc")?>" />
  <link rel="stylesheet" type="text/css" href="/css/style.css?_=10019" />
  <link rel="stylesheet" type="text/css" href="/css/editme.css" />
    <!--[if lt IE 9]>
    <link href="/css/ie8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-3700383-6', 'auto');
        ga('send', 'pageview');
    </script>

</head>
<body class="<?php if ( isset($pagename) ) {echo $pagename;}?> <?php echo 'lang-' . (string)Yii::app()->language; ?>">
	<div class="loading-wrap" style="display:block;"><div class="loading loading_small"></div></div>
	<div class="wrap <?php echo 'lang-' . (string)Yii::app()->language; ?>">
		<?php if ( isset($homepage) ) { ?>
		<?php include_once 'widget/home-slider.php';?>
		<?php } ?>
		<!-- head -->
		<div class="head" <?php if ( isset($pagename) ) { ?>data-page="<?php echo $pagename;?>"<?php } ?>>
			<div class="head-fixed" <?php if( isset($homepage) ){?> style="position:static" <?php } ?>>
				<div class="head-inner-wrap">
					<div class="head-inner cs-clear">
            <a href="javascript:;" class="m-el m-nav" data-a="m-nav"></a>
						<ul class="nav nav1">
							<li data-type="collections"><a data-a="nav-pop" data-d="type=collections" href="javascript:;"><?php echo Yii::t("strings", "COLLECTIONS")?></a><img class="nav-bg" src="/images/nav-bg.jpg"/><div class="nav-mask"></div></li>
							<li data-type="crafts"><a data-a="nav-pop" data-d="type=crafts" href="javascript:;"><?php echo Yii::t("strings", "CRAFTS")?></a><img class="nav-bg" src="/images/nav-bg.jpg"/><div class="nav-mask"></div></li>
							<li data-type="boutiques"><a data-a="nav-pop" data-d="type=boutiques" href="javascript:;"><?php echo Yii::t("strings", "BOUTIQUES")?></a><img class="nav-bg" src="/images/nav-bg.jpg"/><div class="nav-mask"></div></li>
              <li class="last"><a data-a="nav-link" data-d="type=gift" href="<?php echo url("gift-corner")?>"><?php echo Yii::t("strings", "GIFT")?></a><img class="nav-bg" src="/images/nav-bg.jpg"/><div class="nav-mask"></div></li>
						</ul>
			      <h1 class="logo"><a data-a="nav-link" href='/' onclick="javascript:window.location.href='/'"></a></h1>
                  <h1 class="logosmall"><a data-a="nav-link" href='/' onclick="javascript:window.location.href='/'"></a></h1>
						<ul class="nav nav2">
							<li><a data-a="nav-link" href="<?php echo url("news")?>"><?php echo Yii::t("strings", "NEWS")?></a><img class="nav-bg" src="/images/nav-bg.jpg"/><div class="nav-mask"></div></li>
							<li><a data-a="nav-link" href="<?php echo url("about")?>"><?php echo Yii::t("strings", "ABOUT")?></a><img class="nav-bg" src="/images/nav-bg.jpg"/><div class="nav-mask"></li>
							<li class="last"><a data-a="nav-link" href="<?php echo url("contact")?>"><?php echo Yii::t("strings", "CONTACT")?></a></li>
						</ul>
						<!--  -->
						<div class="hd_oter cs-clear">
							<div class="hd_language">
								<a href="javascript:void(0)" class="<?php global $language; if ($language == "cn") echo "active"?>" data-a="chang-lang" data-lang="zh_cn">中文</a>|
                <a href="javascript:void(0)" class="<?php global $language; if ($language == "en") echo "active"?>" data-a="chang-lang" data-lang="en_us" class="on">EN</a>
<!--                <a href="javascript:void(0)" class="<?php global $language; if ($language == "fr") echo "active"?>" data-a="chang-lang" data-lang="fr">FR</a>-->
							</div>
							<a href="#" data-a="show-search-form" class="hd_search"></a>
						</div>
					</div>
				</div>
				<div class="searchform">
					<form action="<?php echo url("search")?>" class="section cs-clear">
						<input type="text" autocomplete="off" placeholder="<?php echo Yii::t("strings" ,"ENTER YOUR SEARCH")?>" name="s" value="">
						<button><?php echo Yii::t("strings" ,"SEARCH")?></button>
					</form>
				</div>
				<div class="nav-pop nav-pop-collections">
          <a href="#" class="m-el m-nav-back" data-a="m-nav-back"></a>
					<div class="nav-pop-inner">
						<div class="nav-pop-nav">
<!--              <p><a herf="#">--><?php //echo Yii::t("strings" ,"Collections")?><!-- <span>&gt;</span></a></p>-->
              <?php foreach (ProductContentAR::getType() as $id => $name):  ?>
              <p><a href="<?php echo url("product-type", array("name" => ProductContentAR::getTypeKeyName($id)))?>"><?php echo ucfirst($name)?> <span>&gt;</span></a></p>
              <?php endforeach;?>
              <p><a href="<?php echo url("gift-corner")?>"><?php echo Yii::t("strings" ,"Gift Corner")?> <span>&gt;</span></a></p>
						</div>
            <div class="nav-pop-wraps">
<!--             <div class="nav-pop-wrap">-->
<!--                <div class="nav-pop-wrap-inner cs-clear">-->
<!--                  --><?php //$collectiones = CollectionContentAR::model()->getList(); ?>
<!--                  --><?php //foreach ($collectiones as $item): ?>
<!--                    <a class="nav-pop-item inout-effect" data-a="nav-link" href="--><?php //echo url("collections", array("cid" => $item->cid)) ?><!--">-->
<!--                      <img src="--><?php //echo $item->nav_image ?><!--"/> -->
<!--                      <span class="nav-text"><i> --><?php //echo $item->title ?><!--</i></span>-->
<!--                      <span class="home-inout-bg inout-bg"></span>-->
<!--                    </a>-->
<!--                  --><?php //endforeach; ?>
<!--                </div>-->
<!--              </div>-->
              <?php foreach (ProductContentAR::getType() as $id => $name): ?>
                <?php $files = loadTypeMedias(ProductContentAR::getTypeKeyName($id));?>
                <div class="nav-pop-wrap">
                  <div class="nav-pop-wrap-inner cs-clear">
                    <?php foreach ($files as $file): ?>
                    <a href="<?php echo url("product-type", array("name" => ProductContentAR::getTypeKeyName($id)))?>" class="nav-pop-item inout-effect" data-a="nav-link">
                        <img src="<?php echo $file ?>"/> 
                        <span class="nav-text"><i> </i></span>
                        <span class="home-inout-bg inout-bg"></span>
                      </a>
                    <?php endforeach;?>
                  </div>
                </div>
              <?php endforeach;?>
                <div class="nav-pop-wrap">
                    <div class="nav-pop-wrap-inner cs-clear">

                        <a href="<?php echo url("gift-corner")?>" class="nav-pop-item inout-effect" data-a="nav-link">
                            <img src="/photo/gift_dpmenu2.jpg"/>
                            <span class="nav-text"><i> </i></span>
                            <span class="home-inout-bg inout-bg"></span>
                        </a>
                        <a href="<?php echo url("gift-corner")?>" class="nav-pop-item inout-effect" data-a="nav-link">
                            <img src="/photo/gift_dpmenu3.jpg"/>
                            <span class="nav-text"><i> </i></span>
                            <span class="home-inout-bg inout-bg"></span>
                        </a>
                        <a href="<?php echo url("gift-corner")?>" class="nav-pop-item inout-effect" data-a="nav-link">
                            <img src="/photo/gift_dpmenu4.jpg"/>
                            <span class="nav-text"><i> </i></span>
                            <span class="home-inout-bg inout-bg"></span>
                        </a>
                    </div>
                </div>
            </div>
				</div>
        </div>
				<div class="nav-pop nav-pop-crafts">
          <a href="#" class="m-el m-nav-back" data-a="m-nav-back"></a>
					<div class="nav-pop-inner">
            <?php $crafts = CraftContentAR::model()->getList();?>
            <?php foreach($crafts as $craft): ?>
            <a class="nav-pop-item inout-effect" data-a="nav-link" href="<?php echo url("craft", array("cid" => $craft->cid))?>" data-order="Reverse">
            	<img src="<?php echo $craft->nav_image?>"/>
            	<span class="nav-text"><i><?php echo $craft->title?></i></span>
            	<span class="home-inout-bg inout-bg"></span>
            </a>
            <?php endforeach;?>
					</div>
				</div>
				<div class="nav-pop nav-pop-boutiques">
          <a href="#" class="m-el m-nav-back" data-a="m-nav-back"></a>
					<div class="nav-pop-inner">
            <?php $first = TRUE;?>
            <?php foreach (BoutiqueContentAR::getLocation() as $key => $name): ?>
              <?php $boutique = BoutiqueContentAR::model()->loadByAddressKey($key);?>
              <?php if ($boutique): ?>
                <a class="nav-pop-item inout-effect" <?php if ($first) echo 'style="margin-left: 12%;"'?> data-a="nav-link" href="<?php echo url("boutique", array("type" => urlencode($key)))?>" data-order="Reverse">
                	<img src="<?php echo ($boutique->nav_image) ?>"/>
                	<span class="nav-text"><i><?php echo $name?></i></span>
                	<span class="home-inout-bg inout-bg"></span>
                </a>
              <?php endif;?>
            <?php $first = FALSE;?>
            <?php endforeach;?>
						</div>
				</div>
			</div>
		</div>
		<div class="nav-mask" data-a="nav-mask"></div>
		










