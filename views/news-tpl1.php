<!-- tpl1 一张大图 新闻模版-->
<div class="picinfor cs-clear">
    <img src="<?php echo $news->news_slide_image_1260x470 ?>" style="max-width:100%;" />

    <div class="picinfortxt2 pic-text-tpl pic-text-tpl1">
        <div class="picinfortxt-inner">
            <h2><?php echo $news->title?></h2>
            <h3 style="text-transform:uppercase;"><?php echo date("Y M d", strtotime($news->date))?></h3>
            <div class="body">
                <?php echo $news->body?>
            </div>
        </div>
        <div style="margin-right:50px;">
            <a href="#" data-a="show-pop" class="btn transition-wrap"><span class="transition"><?php echo Yii::t('strings', "read more")?><br><br><?php echo Yii::t('strings', 'read more')?></span></a>
            <textarea style="display:none;">
                <?php echo $news->body?>
            </textarea>
        </div>
    </div>
</div>