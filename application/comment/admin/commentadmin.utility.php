<?php

class CommentAdminUtility extends AdminUtility{
	
	function comments(){
		
		$dataset =  $this->db->from('comment')
							->select('comment_id', 'content', 'author_name')
							->orderby('created', 'desc');
		
		if($this->node->arg('item')){
			$dataset->where('item', $this->node->arg('item'));
		}
		
		$list = $this->components->add('list', array(
			'table' => 'comment',
			'dataset' => $dataset,
			'operations' => array(
				'edit' => array('comment/comment_edit', $this->lang->edit),
				'delete' => true
			),
			'pagination' => array('auto' => true)
		));
		
		$list->addFilter('dropdown', array('comment', 'status'), $this->lang->status, $this->lang->status_types);
		
	}
	
	function comment_edit(){
		
		$form = $this->components->add('form');
		$comment = $form->addTable('comment', array(
			'item' => array('type' => 'hidden')
		));
		
		$comment->status->setDataSource($this->lang->status_types);
		
		$form->setValues($comment, $this->db->from('comment')->where('comment_id', $this->node->arg('comment_id'))->fetchRow());
		
		
	}
	
	
}