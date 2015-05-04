<?php // tpl2 对应 二张图（ 840x470）上下叠放模版 ?>

<?php 
  $images  = array_slice($news->news_slide_image, 0, 2);
?>
<div class="picinfor cs-clear">
    <div class="picinfortxt news-picinfortxt">
        <div class="picinfortxt-inner">
            <h2><?php echo $news->title?></h2>
            <h3 style="text-transform:uppercase;"><?php echo date("Y M d", strtotime($news->date))?></h3>
            <div class="body">
                <?php echo $news->body?>
            </div>
        </div>
        <div style="margin-right:50px;">
            <a href="#" data-a="show-pop" class="btn transition-wrap"><span class="transition"><?php echo Yii::t('strings', 'read more')?><br><br><?php echo Yii::t('strings', 'read more')?></span></a>
            <textarea style="display:none;">
                <?php echo $news->body?>
            </textarea>
        </div>
    </div>
    <div class="picinforpic">
        <?php foreach ($images as $image): ?>

        <?php endforeach;?>
        <img src="<?php echo @$images[0]?>" width="100%" style="margin-bottom:20px;">
    </div>
    <!--  -->
</div>