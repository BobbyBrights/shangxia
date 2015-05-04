<?php
	// 传递参数
	// title
	// show_item_title
	// show_item_url
	// list =>
          // array(
          // 	array(
          // 		'img' => 'xxxx',
          // 		'width' => 'xxxx',
          // 		'height' => 'xxxx',
          // 		'title' => 'xxx',
		  //		'publish_date' => 'xxxx',
          // 		'buy' => 1,
          // 		)
          // 	)
?>
<div class="collpiclist cs-clear" style="position:relative">
	<div class="collarrows collarrowsprev"></div>
	<!--  -->
	<div class="section">
		<div class="products ">
			<div class="productstit ">
				<h2><?php echo $title?></h2>
			</div>	
			<!--  -->
			<div class="products-wrap js-horizontal-slide intoview-effect" data-effect="fadeup" data-num="3">
				<div class="collarrows collarrowsprev" data-a="collarrowsprev"></div>
				<div class="slide-con">
					<ul class="slide-con-inner piclist cs-clear">

						<?php foreach($list as $item): ?>
		                <div class="prolistitem pressitem intoview-effect" data-effect="fadeup">
	                 	  <?php if( $show_item_url ){ ?>
	                 	  <a href="<?php echo $item->link;?>">
	                 	  <?php } ?>
		                  	<img src="<?php echo makeThumbnail($item->thumbnail, array(600, 570))?>" data-width="" data-height=""  width="100%" />

		                  </a>
		                  <?php if( $show_item_title ){?>
		                  <p><?php echo $item->title?><br /><?php echo date("M Y", strtotime($item->publish_date))?></p>
		                  <?php }?>
		                </div>
		              <?php endforeach;?>


						<li class="piclistitem collpicitem">
							<img src="/images/colldemo3.jpg" width="100%" />
							<p><span>view</span></p>
						</li>
						<li class="piclistitem collpicitem">
							<img src="/images/colldemo3.jpg" width="100%" />
							<p><span>view</span></p>
						</li>
						<li class="piclistitem collpicitem marginR0">
							<img src="/images/colldemo3.jpg" width="100%" />
							<p><span>view</span></p>
						</li>
					</ul>
				</div>
				<div class="collarrows collarrowsnext" data-a="collarrowsnext"></div>
			</div>
		</div>
	</div>
	<!--  -->
	<div class="collarrows collarrowsnext"></div>
</div>