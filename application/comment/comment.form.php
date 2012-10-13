<?php

class CommentForm extends Form {

	function addComment(){

		$this->helper->filter->defaults($this->args,
			array('item_id' => 0)
		);

		$text = $this->add('textarea', 'content');

		$name = $this->add('text', 'name');
		$url = $this->add('text', 'url');
		$email = $this->add('text', 'email');

		$parent_comment_id = $this->add('hidden', 'parent_comment_id', 0);
		$item_id = $this->add('hidden', 'item_id', $this->args['item_id']);

		$this->add('submit', 'submit', 'Submit');
		$this->add('image', 'submit_image', 'Submit');

		$parent_comment_id->setValidator(array(
			'db_in_column' => array('comment', 'comment_id'),
			'validate_if' => $parent_comment_id->getValue() != '0')
		);

		$text->setValidator(
			array('required' => true, 'max_length' => 5000),
			$this->lang->comment_errors
		);

		$name->setValidator($this->valid->required, $this->lang->messages['name']);
		$email->setValidator($this->valid->get('required', 'email'), $this->lang->messages['email']);
		$url->setValidator($this->valid->lossy_url);
		$item_id->setValidator($this->valid->get('required', 'positive_integer'));

		$email->setTarget('comment', 'author_email');
		$url->setTarget('comment', 'author_url');
		$name->setTarget('comment', 'author_name');
		$text->setTarget('comment');
		$item_id->setTarget('comment');
		$parent_comment_id->setTarget('comment');
		
		$url->setFilter(array(&$this, '_fix_comment_url'));
		$text->setFilter(array(&$this, '_prepare_comment'));
		
	}

	function _fix_comment_url($value, $input){
	
		if(!preg_match('/^https?:\/\//', $value)){
			$value = 'http://'.$value;
		}

		return $value;

	}

	function _prepare_comment($comment, $level){
		$comment = nl2br($this->load->helper('html')->escape($comment));
		$reg = "(https?://[0-9A-Za-z_-]+([\.][0-9A-Za-z_-]+)+([/][0-9A-Za-z_~:\.,#\?@%&+=-]*)*)";
		$replacement = '<a href="${0}" target="_blank" rel="nofollow">${0}</a>';
		$comment = preg_replace($reg, $replacement, $comment);
		$comment = preg_replace("([^\s(<.*?>)]{50})", '${0} ', $comment);
		return $comment;
	}
	
}

