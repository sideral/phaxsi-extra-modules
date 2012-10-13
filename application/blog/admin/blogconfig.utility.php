<?php

class BlogConfigUtility extends AdminConfigUtility{
	
	function configureSchema($schema){
		
		$schema->addTable('blog', array(
			'blog_id'		=> array('primary'),
			'name'			=> array('string', 'Name'),
			'description'	=> array('text', 'Description'),
			'theme'			=> array('string', 'theme'),
			'created'		=> array('timestamp', 'Created')
		));

		$blog_post = $schema->addTable('blog_post',	array(
			'blog_post_id'		=> array('primary'),
			'blog_id'			=> array('key'),
			'blog_author_id'	=> array('key'),
			'title'				=> array('char', $this->lang->fields['title']),
			'pub_date'			=> array('datetime', $this->lang->fields['pub_date']),
			'summary'			=> array('html', $this->lang->fields['summary']),
			'content'			=> array('html', $this->lang->fields['content']),
			'blog_category_id'	=> array('key', $this->lang->fields['category']),
			'status'			=> array('enum', $this->lang->fields['status']),
			'enabled'			=> array('boolean', $this->lang->fields['enabled']),
			'modified'			=> array('timestamp')
		));
		
		$blog_post->blog_id->references('blog', 'blog_id', AdminTable::ON_DELETE_CASCADE);
		$blog_post->blog_author_id->references('blog_author', 'blog_author_id', AdminTable::ON_DELETE_CASCADE);
		$blog_post->blog_category_id->references('blog_category', 'blog_category_id', AdminTable::ON_DELETE_SET_NULL);

		$schema->addTable('blog_post_tag', 
			array('blog_post_id'	=> array('primary'),
				  'blog_tag_id'		=> array('primary', $this->lang->fields['tags'])),
			array('blog_post_id'	=> array('blog_post', 'blog_post_id', AdminTable::ON_DELETE_CASCADE),
				  'blog_tag_id'		=> array('blog_tag', 'blog_tag_id', AdminTable::ON_DELETE_CASCADE))
		);

		$schema->addTable('blog_tag', array(
			'blog_tag_id'	=> array('primary'),
			'tag'			=> array('char', 'Tag'),
			'url_tag'		=> array('varchar', 'Tag Url')
		));

		$blog_author = $schema->addTable('blog_author', array(
			'blog_author_id' => array('primary'),
			'name'			=> array('char',  $this->lang->fields['name']),
			'user_id'		=> array('key'),
			'created'		=> array('timestamp')
		));
		
		$blog_author->user_id->references('user', 'user_id', AdminTable::ON_DELETE_NONE);	
		
		$blog_category = $schema->addTable('blog_category',
			 array(
				'blog_category_id' => array('primary', 'blog_category_id'), 
				'name' => array('varchar',  $this->lang->fields['name']), 
				'blog_id' => array('key', 'blog_id') 
			)
		);
		
		$blog_category->blog_id->references('blog', 'blog_id', AdminTable::ON_DELETE_CASCADE);
		
	}
	
	
}