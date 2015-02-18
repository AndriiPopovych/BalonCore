<div class="page_name">
	<h1>Світ</h1>
	<div class="page_info">
		<span class="date">8 березня, 10:33</span>
		<span class="num_view">1</span>
	</div>
</div>	

<section class="preview_bl page clearfix p-rel">
	<div class="preview_img">
		<img src="lib/pic/sidebar/splin.png" alt="" />
		<div class="ttl">
			Составлена карта кинематогра-фических бедствий
		</div>
	</div>
	<div class="preview_cont text">
		<p>	В интернете опубликовали карту США, 
			на которой показано, какие города 
			и штаты чаще всего страдают 
			от различных бедствий в 
			художественных фильмах. 
			Рекордсменом стал Нью-Йорк — 
			на город было совершено 35 атак, 
			причем чаще всего на него нападали 
			гигантские монстры. На втором
			 месте оказался Лос-Анджелес
			 (34 атаки), на третьем —
			 Сан-Франциско (21 атака).
		</p>
		<a href="">Читати далі >></a>
	</div>	
</section>

<section class="page_advertising p-rel">
	<span class="caption p-abs">реклама</span>
	<a class="p-rel" href="">
		<div class="dvertising_content">
			Ні, я жива, я буду вічно жити, я в серці маю те що вічно не вмирає asdsada
		</div>
		<span class="dvertising_name">
			Леся Якраїнка - "Лісова пісня"
		</span>
		<img class="p-abs" src="lib/pic/sidebar/splin.png" alt="" />
	</a>
</section>


<section class="news_bl">
	<div class="type_news">
		<a class="active" href="">останні</a>
		<a href="">популярні</a>
	</div>

		<?php foreach ($data as $day => $result) { ?>
			<div class="page news_container">
				<div class="news_title">
					<h1 class="ttl_mini big_ttl"><?=$day?></h1>
				</div>
				<?php foreach ($result as $key => $val) { ?>
				<div class="news_item">
					<div class="archive_date_bl">
						<div class="archive_time"><?=$val['time'];?></div>
						<div class="archive_day"><?=$day;?></div>
					</div>
					<div class="archive_content">
						<a href="">
							<span class="ttl"><?=$val['title'];?></span>
							<div class="num_view"><?=$val['views'];?></div>
						</a>
						<div class="text">
							<?=$val['text'];?>
						</div>
					</div>
				</div>
				<?php } ?>

			</div>

		<?php } ?>

	<div class="page_navigation">
		<ul>
			<li>
				<a class="active" href="">1</a>
			</li>
			<li>
				<a href="">2</a>
			</li>
			<li>
				<a href="">3</a>
			</li>
			<li>
				<a href="">4</a>
			</li>
			<li>
				<a href="">5</a>
			</li>
			<li>
				...
			</li>
			<li>
				<a href="">25</a>
			</li>
		</ul>
	</div>
</section>