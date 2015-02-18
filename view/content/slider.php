<div class="slider">
	<div class="slider_container p-rel">
		<?php Control::add("slider"); ?>
		<?php foreach ($data['slider'] as $key => $val) { ?>
			<div class="slide_item clearfix p-abs <?php if ($key == 0) echo "slide__visible"; ?>">
				<?php Control::controllers(['edit','remove'],"slider",$val['id']); ?>
				<div class="slide_img left">
					<img src="/lib/image/slider/<?=$val['image'];?>" alt="" />
				</div>
				<div class="slide_content left">
					<h2>
						<a href="<?=$val['href'];?>"><?=$val['title'];?></a>
					</h2>
					<div class="content">
						<?=$val['text'];?>
					</div>
				</div>
			</div>
		<?php } ?>

		<div class="slider_control p-abs">
			<?php foreach ($data['slider'] as $key => $val) {?>
			<li class="<?php if (!$key) echo "slider_control__active"; ?>"></li>
			<?php } ?>
		</div>
	</div>
</div>