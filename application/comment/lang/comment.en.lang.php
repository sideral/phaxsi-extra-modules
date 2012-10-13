<?php

class CommentLang extends Lang{

	public $comment_errors = array('required' => "Your comment must not be left blank");

	public $no_comments = "There are no comments yet";

	public function timeAgo($timestamp){
		return 'Posted '. DateTimeHelper::timeAgoString($timestamp, 1) . ' ago';
	}
	
	public $edit = 'Edit Comment';

	public $comment = 'Comment';
	public $email = 'Email';
	public $website = 'Website';
	public $name = 'Name';
	public $status = 'Status';

	public $messages = array(
		'email' => array(
			'required' => 'Email is required',
			'expression' => 'Email is invalid'
		),
		'name' => array(
			'required' => 'Name is required'
		)
	);
	
	public $fields = array(
		'content' => 'Content',
		'author_name' => 'Author Name',
		'author_email' => 'Author Email',
		'author_url' => 'Author URL',
		'author_ip' => 'Author IP'
	);
	
	public $status_types = array(
		'pending' => 'Pending',
		'accepted' => 'Accepted',
		'rejected' => 'Rejected'
	);
	
}

