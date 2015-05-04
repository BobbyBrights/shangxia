<!-- slide -->
<div id="home-slider">
	<div class="slide"  data-slide="absolute" >
    <?php $slideshow = loadContentList("slideShow"); ?>
		<ul class="slidebox cs-clear">
      <?php foreach ($slideshow as $show): ?>
        <li class="slideitem">
          <a href="<?php echo $show->link?>"><img src="<?php echo makeThumbnail($show->image)?>" width="100%" /></a>
          <div class="slidetip">
            <h4 class="tit"><?php echo $show->title?></h4>
            <p><?php echo $show->body?></p>
          </div>
        </li>
      <?php endforeach;?>
		</ul>
		<ul class="slidetab">
      <?php foreach ($slideshow as $key => $show): ?>
			<li class="<?php if ($key == 0) echo "on"?>"></li>
      <?php endforeach;?>
		</ul>
		<!-- 数量改变需要改变css，或者用js来调整slidebox的宽度和slidetab的位置 -->
	</div>
</div>