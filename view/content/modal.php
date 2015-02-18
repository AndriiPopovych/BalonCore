<div class="modal_bg">
	<div class="modal_window" id="mod_edit">
		<div class="mod_header">
			Додати новий запис
		</div>
		<div class="mod_content">
			<form enctype="multipart/form-data" action="">
				<div class="inp_container">
					<input type="radio" id="r1" name="r" />
					<label class="inp_label_radio" for="r1">Радіо кнопка 1</label>
				</div>
				<div class="inp_container">
					<input type="radio" id="r2" name="r" />
					<label class="inp_label_radio" for="r2">Радіо кнопка 2</label>
				</div>
				<div class="inp_container">
					<input type="radio" id="r3" name="r" />
					<label class="inp_label_radio" for="r3">Радіо кнопка 3</label>
				</div>

				<div class="inp_container">
					<input type="checkbox" id="ch1" name="ch1" />
					<label class="inp_label_checkbox" for="ch1">Радіо кнопка 1</label>
				</div>
				<div class="inp_container">
					<input type="checkbox" id="ch2" name="ch2" />
					<label class="inp_label_checkbox" for="ch2">Радіо кнопка 2</label>
				</div>
				<div class="inp_container">
					<input type="checkbox" id="ch3" name="ch3" />
					<label class="inp_label_checkbox" for="ch3">Радіо кнопка 3</label>
				</div>
				<div class="textarea">
					<textarea></textarea>
				</div>
				<div class="file_upld">
					Завантажити файл
					<input type="file" />
				</div>
			</form>
		</div>
		<div class="mod_footer">
			<button class="ok">OK</button>
		</div>
		<i class="i-close">[x]</i>
	</div>

	<div class="modal_window blocked" id="mod_edit2">
		<div class="mod_header">
			Додати новий запис
		</div>
		<div class="mod_content">
			<form enctype="multipart/form-data" action="">
				<div class="file_upld">
					Завантажити файл
					<input type="file" />
				</div>
			</form>
		</div>
		<div class="mod_footer">
			<button class="ok">OK</button>
		</div>
		<i class="i-close">[x]</i>
	</div>

	<div class="modal_window" id="mod_signIn">
		<div class="mod_header">
			Реєстрація
		</div>
		<div class="mod_content clearfix">
			<div class="column1 left">
				<form enctype="multipart/form-data" action="">
					<p>
						<span>E-mail</span>
						<input type="text" />
					</p>
					<p>
						<span>Пароль</span>
						<input type="text" />
					</p>
					<p>
						<input type="checkbox" id="ch" name="ch" />
						<label class="inp_label_checkbox" for="ch">Запамятати мене</label>
					</p>
				</form>
			</div>
			<div class="column2 left t-center">
				<span>або увійдіть через соцмережі</span>
				<a href="">
					<img src="lib/pic/vk_m.png" alt="" />
					<img src="lib/pic/FB_m.png" alt="" />
				</a>
			</div>
		</div>
		<div class="mod_footer">
			<input type="submit" value="Увійти" class="ok cur-p" />
		</div>
		<i class="i-close">[x]</i>
	</div>

	<div class="modal_window" id="mod_signUp">
		<div class="mod_header">
			Підписка
		</div>
		<div class="mod_content clearfix p-rel">
			<form enctype="multipart/form-data" action="">
				<div class="clearfix">
					<div class="column1 left">
						<p>
							<span>E-mail</span>
							<input type="text" />
						</p>
						<p>
							<span>Пароль</span>
							<input type="text" />
						</p>
					</div>
					<input type="submit" value="Підписатись" class="ok cur-p p-abs bottom right" />
				</div>
			</form>
		</div>
		<i class="i-close">[x]</i>
	</div>

	<div class="modal_window" id="mod_photo">
		<div class="mod_content p-rel">
			<div class="mod_num_photo p-abs"></div>
			<div class="mod_next_photo p-abs cur-p">
				<img src="lib/pic/arrow-slide.svg" alt="" />
			</div>
			<div class="mod_prev_photo p-abs cur-p">
				<img src="lib/pic/arrow-slide.svg" alt="" />
			</div>
			<div class="mod_photo_container p-rel">
				<ul>
					<li class="mod_slide__visible">
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
		</div>
	</div>
</div>