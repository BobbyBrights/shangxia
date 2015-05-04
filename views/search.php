<?php 
if( !isset( $_GET['type'] ) ){
	$_GET['type'] = 'all';
}
include_once 'common/header.php';

$results = searchWithKeyword($_GET["s"]);
if (!$results) {
  $results = array();
}
?>
		<!-- detail -->
		<div class="section ">
			<div class="detail cs-clear">
					<h2 class="">search：<?php echo $_GET['s']; ?></h2>
			</div>
		</div>
		<!-- search nav -->
		<div class="searchnav ">
			<div class="searchnavcom cs-clear">
				<a data-a="search-type" data-d="type=all" href="/search.php?s=<?php echo $_GET['s']; ?>&type=all" class="<?php if($_GET['type'] == 'all'){echo 'on';} ?>">all</a>
				<a data-a="search-type" data-d="type=collection" href="/search.php?s=<?php echo $_GET['s']; ?>&type=collection" class="<?php if($_GET['type'] == 'collection'){echo 'on';} ?>">collection</a>
				<a data-a="search-type" data-d="type=craft" href="/search.php?s=<?php echo $_GET['s']; ?>&type=craft" class="<?php if($_GET['type'] == 'craft'){echo 'on';} ?>">craft</a>
			</div>
		</div>
		<!-- searchlist -->
		<div class="section">
			<div class="products searchlist">
				<!--  -->
        <?php if (!count($results)): ?>
          <div class="piclist cs-clear" id="search-result">
            <p style="text-align: center;font-size: 24px;"><?php echo Yii::t("strings", "Sorry, the result of your search is empty, please try again with other keywords.")?></p>
          </div>
        <?php else: ?>
          <div class="">
            <ul class="piclist cs-clear" id="search-result">
              <?php foreach ($results as $item): ?>
              <?php if ($item->type == "product"):?>
                <li class="piclistitem searchpicitem" data-type="<?php echo $item->type == "product" ? "collection": $item->type?>">
                  <a data-a="nav-link" href="<?php echo url("product-detail", array("cid" => $item->cid)) ?>">
                    <img src="<?php echo makeThumbnail($item->thumbnail, array(415, 220))?>" width="100%" />
                    <p><span><?php echo $item->title?></span></p>
                  </a>
                </li>
              <?php else:?>
                <li class="piclistitem searchpicitem" data-type="<?php echo $item->type?>">
                  <a data-a="nav-link" href="<?php echo url($item->type == "collection" ? "collections": "craft", array("cid" => $item->cid)) ?>">
                    <img src="<?php echo makeThumbnail($item->thumbnail_image, array(415, 220))?>" width="100%" />
                    <p><span><?php echo $item->title?></span></p>
                  </a>
                </li>
              <?php endif;?>
              <?php endforeach;?>
            </ul>
          </div>
        <?php endif;?>
			</div>
		</div>
		<!--  -->
<?php include_once 'common/footer.php';?>