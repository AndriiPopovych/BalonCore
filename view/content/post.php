<div class="page_post">

	<div class="page_name">
		<h1>Відео</h1>
		<div class="page_info">
			<a class="tag" href=""><?=$data['name']?></a>
			<span class="date"><?=$data['create_date']?></span>
			<span class="num_view"><?=$data['views']?></span>
			<span class="right cur-p btn-print 		js_bnt_print"></span>
			<span class="right cur-p bnt-font_plus	js_bnt_font_plus">A+</span>
			<span class="right cur-p bnt-font_minus js_bnt_font_minus">a-</span>
		</div>
	</div>	

	<div class="post_content">
		<h1><?=$data['title']?></h1>
		<!--div class="post_photo_carusel p-rel">
			<div class="prev_post_photo p-abs cur-p">
				<img src="lib/pic/arrow-slide-prev.svg" alt="" />
			</div>
			<div class="next_post_photo p-abs cur-p">
				<img src="lib/pic/arrow-slide.svg" alt="" />
			</div>
			<div class="author_post_photo p-abs">Photo: author</div>
			<div class="num_post_photo p-abs">1/23</div>
			<div class="post_photo_carusel_container p-rel cur-p">
				<ul>
					<li class="gallery_slide__visible">
						<img src="/lib/pic/sidebar/splin.png" alt="" />
					</li>
					<li>
						<img src="/lib/pic/sidebar/splin1.png" alt="" />
					</li>
					<li>
						<img src="/lib/pic/sidebar/splin2.png" alt="" />
					</li>
				</ul>
			</div>
		</div-->
		<div class="preview_bl clearfix p-rel">
			<div class="preview_img">
				<img src="/lib/image/news/<?=$data['image'];?>" alt="" />
			</div>
			<div class="preview_cont text">
				Наиболее “катастрофичным” признали американский город Нью-Йорк
			</div>
			<div class="photo_autor p-abs">Фото: Reuters</div>
		</div>
		<div class="post_more">
			<p>
			<?=$data['text']?>
			</p>
			<!--p>
				В интернете опубликовали карту США, на которой показано, 
				какие города и штаты чаще всего страдают от различных бедст-
				вий в художественных фильмах. Рекордсменом стал Нью-Йорк —
				на город было совершено 35 атак, причем чаще всего на него 
				нападали гигантские монстры. 
			</p>
			<p>
				На втором месте оказался Лос-Анджелес (34 атаки), на 
				третьем —Сан-Франциско (21 атака).
			</p-->
		</div>
		<div class="post_text">
			<h6>Сховище даних</h6>
			<p>
				<?=$data['text']?>
			</p>
			<p>
				Сховище даних (англ. data warehouse) — предметно орієнтований, інтегрований, 
				незмінний набір даних, що підтримує хронологію і здатний бути комплексним дже-
				релом достовірної інформації для оперативного аналізу та прийняття рішень. В 
				основі концепції сховища даних (СД) лежить розподіл інформації, що використо-
				вують в системах оперативної обробки даних (OLTP) і в системах підтримки прий-
				няття рішень (СППР).
			</p>
			<img src="/lib/pic/sidebar/splin.png" alt="" />
			<p>
				Data Warehouse повинен мати структуру форми зірочки ( star model ) або сніжинки 
				( snowflake model ) і складатися з фактів ( facts ) і вимірювань ( dimensions ) .
				Факти - це фактичні записи ( records ) про якесь процесі , який ми хочемо аналі-
				зувати , наприклад , процес голосування , або процес зміни ціни товару на біржі. 
				Дуже часто факти містять які-небудь числові дані , наприклад , фактичне значення
				 голосу або ціни .
			</p>
			<img src="/lib/pic/sidebar/splin.png" alt="" />
			<p>
				Вимірювання - це визначальні атрибути фактів , і зазвичай відповідають на всякі 
				питання : коли стався факт , над чим або з чим саме , хто був об'єктом або суб'єк-
				том і т.п. В основному , вимірювання мають більш описовий (тобто текстовий) ха-
				рактер , наприклад , ім'я користувача або назва місяця , так як кінцевому корис-
				тувачеві буде набагато легше сприймати результати описані текстом ( наприклад , 
				назва місяця ), ніж цифрами (номер місяця в році ) .  
				База даних (скорочено — БД) — впорядкований набір логічно взаємопов'язаних 
				даних, що використовуються спільно та призначені для задоволення інформацій-
				них потреб користувачів. 
			</p>
			<img src="/lib/pic/sidebar/splin.png" alt="" />
			<img src="/lib/pic/sidebar/splin.png" alt="" />
			<p>
				MongoDB — документо-орієнтована система керування базами даних (СКБД) з 
				відкритим вихідним кодом, яка не потребує опису схеми таблиць. MongoDB займає 
				нішу між швидкими і масштабованими системами, що оперують даними у форматі
				 ключ/значення, і реляційними СКБД, функціональними і зручними у формуванні 
				запитів.
				MongoDB підтримує зберігання документів в JSON-подібному форматі, має досить 
				гнучку мову для формування запитів, може створювати індекси для різних збере-
				жених атрибутів, ефективно забезпечує зберігання великих бінарних об'єктів, 
				підтримує журналювання операцій зі зміни і додавання даних в БД, може працю-
				вати відповідно до парадигми Map/Reduce, підтримує реплікацію і побудову відмо-
				востійких конфігурацій.
			</p>
			<h6>Контрольні запитання</h6>
		</div>
	</div>

	<div class="post_info">
		<div>
			
		</div>
	</div>

	<div class="related_news">
		<h4 class="ttl">Схожі новини</h4>
		<ul>
			<li>
				<a href="">Абелевскую премию дали за работы по гипотезам Вейля</a>
				<span class="relat_date">23.04.2014</span>
			</li>
			<li>
				<a href="">Абелевскую премию дали за работы по гипотезам Вейля</a>
				<span class="relat_date">23.04.2014</span>
			</li>
			<li>
				<a href="">Абелевскую премию дали за работы по гипотезам Вейля</a>
				<span class="relat_date">23.04.2014</span>
			</li>
			<li>
				<a href="">Абелевскую премию дали за работы по гипотезам Вейля</a>
				<span class="relat_date">23.04.2014</span>
			</li>
			<li>
				<a href="">Абелевскую премию дали за работы по гипотезам Вейля</a>
				<span class="relat_date">23.04.2014</span>
			</li>
			<li>
				<a href="">Абелевскую премию дали за работы по гипотезам Вейля</a>
				<span class="relat_date">23.04.2014</span>
			</li>
			<li>
				<a href="">Абелевскую премию дали за работы по гипотезам Вейля</a>
				<span class="relat_date">23.04.2014</span>
			</li>
		</ul>
	</div>
</div>