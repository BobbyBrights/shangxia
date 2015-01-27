<?php // tpl3 对应 二张图（ 840x470）错开的模版 ?>

<?php $images  = array_slice($news->news_slide_image, 0, 2); ?>
<div class="picinfor cs-clear">
    <div class="picinfortxt3">
            <h2><?php echo $news->title?></h2>
            <h3 style="text-transform:uppercase;"><?php echo date("Y M d", strtotime($news->date))?></h3>
            <div class="body">
                <?php echo $news->body?>
            </div>
    </div>
    <div class="picinforpic">
        <img src="<?php echo @$images[0]?>" width="100%">
    </div>
    <!--  -->
</div>

<div class="picinfor picinfor2 cs-clear">
    <div class="picinfortxt3 news-pic-tpl3" style="float:right;">
        <div class="body">
            <?php echo $news->desc_two?>
        </div>
    </div>
    <div class="picinforpic" style="float:left;">
        <img src="<?php echo @$images[1]?>" width="100%">
    </div>
    <!--  -->
</div>