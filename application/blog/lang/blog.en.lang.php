<?php

class BlogLang extends Lang{

	public $fields = array(
		'title' => 'Title',
		'pub_date' => 'Publication Date',
		'pub_month' => 'Publication Month',
		'content' => 'Content',
		'summary' => 'Summary',
		'status' => 'Status',
		'enabled' => 'Enabled',
		'tags' => 'Tags',
		'separated' => 'Comma-separated',
		'author' => 'Author',
		'category' => 'Category',
		'name' => 'Name'
	);
	
	public $posts = array(
		'blog_posts' => 'Blog Posts',
		'tag' => 'Tag',
		'new_post' => 'New Post',
		'messages' => array(
			'add' => array('The post was added', 'There was an error adding the post'),
			'edit' => array('The post was updated', 'There was an error updating the post'),
			'delete' => array('The post was deleted', 'There was an error deleting the post')
		)
	);
	
	public $categories = array(
		'categories' => 'Categories',
		'add' => 'Add Category',
		'select' => '- Select -'
	);
	
	public $tags = array(
		'tags' => 'Tags',
		'edit' => 'Edit Tag'
	);
	
	public $status = array(
		'' => '- Select -',
		'published' => 'Published',
		'draft' => 'Draft'
	);

	public $months = array( 1=>'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September','October', 'November', 'December');
	
}