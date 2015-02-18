<div class="modal">

	<div class="modal_bg">
		<div class="modal_window" id="mod_xxx">
			<form class="js-modal_form" method="POST" action="">
				<div class="modal__header">
					<h3 class="modal__title">Заголовок</h3>
				</div>
				<div class="modal__content">
					<p>
						Якийсь тексті
					</p>
				</div>
				<div class="modal__footer">
					<input class="btn btn--full btn--green" type="submit" value="Відправити"/>
				</div>
			</form>
			<button class="icon-close js-mod_close"></button>
		</div>

		<div class="modal_window" id="mod_edit">
			<form class="edit_form" method="POST" action="<?=SITE?>core/index.php" enctype="multipart/form-data" >
				<div class="modal__header">
					<h3 class="modal__title">Заголовок</h3>
				</div>
				<div class="modal__content">
					<p>
						Якийсь тексті
					</p>
				</div>
				<div class="modal__footer">
					<input class="btn btn--full btn--green" type="submit" value="Відправити"/>
				</div>
			</form>
			<button class="icon-close js-mod_close"></button>
		</div>
	</div>

</div>

</body>
</html>