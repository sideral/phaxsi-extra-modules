<ol class="comment-list">
	<?php foreach($serialized_comments as $comment): ?>

		<li class="comment comment-depth-<?= $comment['level']; ?>" id="comment-<?= $comment['comment_id'] ?>">

			<?= $html->absoluteImg('http://www.gravatar.com/avatar/'.md5($comment['author_email']).'?s=32&d=identicon', $comment['author_name'], array('class' => 'avatar')); ?>

			<cite>
				<?php if($comment['author_url']): ?>
					<?= $html->absoluteLink($comment['author_name'], $comment['author_url'], array('class' => 'comment-author', 'rel' => 'external nofollow')); ?>
				<?php else: ?>
					<?= $html->escape($comment['author_name']); ?>
				<?php endif; ?>
			</cite>

			<div class="comment-content">
				<?= $comment['content']; ?>
			</div>
			<small class="comment-metadata">
				<?php if($show_reply && $comment['level'] < $depth): ?>
					<?= $html->absoluteLink('Reply', 'javascript:void(0)', array('class' => "comment-reply-link")); ?>
					|
				<?php endif; ?>
				<?= $html->link($this->lang->timeAgo($comment['created']), '#comment-'.$comment['comment_id']); ?>
			</small>

		</li>

	<?php endforeach; ?>
</ol>

<?php if(!$serialized_comments): ?>
	<div class="no-comments">
		<?= $this->lang->no_comments; ?>
	</div>
<?php endif; ?>

