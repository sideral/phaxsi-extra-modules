<div class="orage-comment-form">

	<?= $form->open(); ?>

		<?= $form->parent_comment_id; ?>
		<?= $form->item_id; ?>

		<dl>
			<dt>
				<?= $form_helper->label($this->lang->name, $form->name); ?>
			</dt>
			<dd>
				<?= $form->name; ?>
				<?= $form_helper->componentErrorMessage($form->name); ?>
			</dd>
			<dt>
				<?= $form_helper->label($this->lang->email, $form->name); ?>
			</dt>
			<dd>
				<?= $form->email; ?>
				<?= $form_helper->componentErrorMessage($form->email); ?>
			</dd>
			<dt>
				<?= $form_helper->label($this->lang->website, $form->name); ?>
			</dt>
			<dd>
				<?= $form->url; ?>
				<?= $form_helper->componentErrorMessage($form->url); ?>
			</dd>
			<dt>
				<?= $form_helper->label($this->lang->comment, $form->name); ?>
			</dt>
			<dd>
				<?= $form->content; ?>
				<?= $form_helper->componentErrorMessage($form->content); ?>
			</dd>
			<dt>
			</dt>
			<dd>
				<?= $form->submit; ?>
			</dd>
		</dl>

	<?= $form->close(); ?>

</div>
