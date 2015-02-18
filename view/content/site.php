<div class="title_line p-rel">
	Головне
</div>


<?php
	include('view/content/slider.php');
?>




<?php/*
блок з рекламою
*/?>
<section class="page_advertising p-rel">
	<span class="caption p-abs">реклама</span>
	<a class="p-rel" href="">
		<div class="dvertising_content">
			Ні, я жива, я буду вічно жити, я в серці маю те що вічно не вмирає asdsada
		</div>
		<span class="dvertising_name">
			Леся Якраїнка - "Лісова пісня"
		</span>
		<img class="p-abs" src="/lib/pic/sidebar/splin.png" alt="" />
	</a>
</section>

<?php/*
блок з розділами
потрібно виводити два послідовно, потім можна тулити рекламу
клас m_right ставиться тільки в одного блоку (першого)
*/?>
<section class="home_item left m_right">
	<?php Control::add("news",['id_chapter' => 1]); ?>
	<h3>Політика</h3>

	<?php foreach ($data['politics'] as $key => $val) { ?>

	<div class="home_post_item">
		<?php Control::controllers(['edit','visibility','remove'],'news',['id' => $val['id']],$val['visibility']) ?>
		<?php if ($val['image']) { ?>
			<div class="home_post_img">
				<img src="/lib/image/news/<?=$val['image'];?>" alt="" />
			</div>
		<?php } ?>
		<div class="home_post_content">
			<div class="text">
				<a href="<?=SITE."News/Politic?id=".$val['id'];?>"><?=$val['title']?></a>
			</div>
			<div class="page_info">
				<span class="date"><?=$val['create_date']?></span>
				<span class="num_view"><?=$val['view'];?></span>
			</div>
		</div>
	</div>

	<?php } ?>
</section>

<section class="home_item left">
	<h3>Економіка</h3>
	<div class="home_post_item">
		<div class="home_post_img">
			<img src="/lib/pic/sidebar/splin.png" alt="" />
		</div>
		<div class="home_post_content">
			<div class="text">
				<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
			</div>
			<div class="page_info">
				<span class="date">8 березня, 10:33</span>
				<span class="num_view">1</span>
			</div>
		</div>
	</div>

	<div class="home_post_item">
		<div class="home_post_img">
			<img src="/lib/pic/sidebar/splin.png" alt="" />
		</div>
		<div class="home_post_content">
			<div class="text">
				<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
			</div>
			<div class="page_info">
				<span class="date">8 березня, 10:33</span>
				<span class="num_view">1</span>
			</div>
		</div>
	</div>

	<div class="home_post_item">
		<div class="home_post_content">
			<div class="text">
				<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
			</div>
			<div class="page_info">
				<span class="date">8 березня, 10:33</span>
				<span class="num_view">1</span>
			</div>
		</div>
	</div>

	<div class="home_post_item">
		<div class="home_post_content">
			<div class="text">
				<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
			</div>
			<div class="page_info">
				<span class="date">8 березня, 10:33</span>
				<span class="num_view">1</span>
			</div>
		</div>
	</div>
</section>

<?php/*
блок з великим розділом на всю ширину сторінки
*/?>
<section class="home_item full left clearfix">
	<h3>Світ</h3>
	<?php/*	перша колонка */?>
	<div class="home_big_post_item col2 m_right left">
		<img src="/lib/pic/sidebar/splin.png" alt="" />
		<div class="home_post_content">
			<div class="text">
				<a href="" class="ttl">Премєра нового альбому групи "Сплін"</a>
				<span>Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</span>
			</div>
			<div class="page_info">
				<span class="date">8 березня, 10:33</span>
				<span class="num_view">1</span>
			</div>
		</div>
	</div>
	<?php/*	друга колонка */?>
	<div class="col2 left">
		<div class="home_post_item">
			<div class="home_post_img">
				<img src="/lib/pic/sidebar/splin.png" alt="" />
			</div>
			<div class="home_post_content">
				<div class="text">
					<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
				</div>
				<div class="page_info">
					<span class="date">8 березня, 10:33</span>
					<span class="num_view">1</span>
				</div>
			</div>
		</div>

		<div class="home_post_item">
			<div class="home_post_img">
				<img src="/lib/pic/sidebar/splin.png" alt="" />
			</div>
			<div class="home_post_content">
				<div class="text">
					<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
				</div>
				<div class="page_info">
					<span class="date">8 березня, 10:33</span>
					<span class="num_view">1</span>
				</div>
			</div>
		</div>

		<div class="home_post_item">
			<div class="home_post_content">
				<div class="text">
					<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
				</div>
				<div class="page_info">
					<span class="date">8 березня, 10:33</span>
					<span class="num_view">1</span>
				</div>
			</div>
		</div>

		<div class="home_post_item">
			<div class="home_post_content">
				<div class="text">
					<a href="">Рок-група «Сплін» в 2014 році випускає свій дванадцатий студійний альбом.</a>
				</div>
				<div class="page_info">
					<span class="date">8 березня, 10:33</span>
					<span class="num_view">1</span>
				</div>
			</div>
		</div>
	</div>
</section>
