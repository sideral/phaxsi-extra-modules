<?php 

class CommentConfigUtility extends AdminConfigUtility{
	
	function configureSchema($schema){		
	
		$comment = $schema->addTable('comment',
			 array(
				'comment_id' => array('primary', 'comment_id'), 
				'item' => array('varchar', 'item'), 
				'item_id' => array('key', 'item_id'), 
				'content' => array('text', $this->lang->fields['content']), 
				'parent_comment_id' => array('key', 'parent_comment_id'), 
				'author_name' => array('varchar', $this->lang->fields['author_name']), 
				'author_email' => array('varchar', $this->lang->fields['author_email']), 
				'author_url' => array('varchar', $this->lang->fields['author_url']), 
				'author_ip' => array('varchar', $this->lang->fields['author_ip']), 
				'user_id' => array('key', 'user_id'), 
				'status' => array('enum', $this->lang->status), 
				'created' => array('timestamp', 'created') 
			)
		);
		
		$comment->parent_comment_id->references('comment', 'comment_id', AdminTable::ON_DELETE_SET_NULL);
		$comment->user_id->references('user', 'user_id', AdminTable::ON_DELETE_CASCADE);

	}
	
}
