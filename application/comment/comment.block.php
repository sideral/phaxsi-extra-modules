<?php

class CommentBlock extends Block {

	function comments(){

		$this->helper->filter->defaults(
			$this->args,
			array('item' => '', 'item_id' => 0, 'title'=> '',
				  'depth' => 3, 'reverse' => false, 'reply' => true,
				  'limit' => 0, 'page' => 1, 'link' => '', 'template' => '')
		);
		
		if($this->args['template']){
			$this->view->setTemplate($this->args['template']);
		}

		$comment_tree = $this->db->Comment->getItemComments($this->args['item'], $this->args['item_id'], $this->args['reverse'], $this->args['limit'], $this->args['page']);

		$this->view->set('serialized_comments', $this->serialize($comment_tree, $this->args['reverse']));	

		$this->view->set('show_reply', $this->args['reply']);

		if($this->args['limit']){
			$this->view->set('count', $this->db->Comment->getBaseCount($this->args['item'], $this->args['item_id']));
		}

		$this->view->setArray($this->args);

	}

	function commentform(){

		$this->helper->filter->defaults($this->args,
				array('item' => '', 'item_id' => 0, 'reverse' => false, 'template' => ''));

		if($this->args['template']){
			$this->view->setTemplate($this->args['template']);
		}

		$params = array('item_id' => $this->args['item_id']);

		$form = $this->view->set('form', $this->load->form('addComment', null, $params));
		$form->setAction('/comment/add_process/'.$this->args['item']);

		$this->view->setArray($this->args);
		
		$this->view->addHelper('form_helper', 'form');
		
	}

	private function serialize(&$comments, $reverse = true){	

		$output = array();

		if($reverse){
			$comments = array_reverse($comments);
		}

		$this->traverse($comments, 0, $output);

		return $output;

	}

	private function traverse(&$comments, $level, &$output){
		foreach($comments as &$comment){
			$comment['level'] = $level;
			$output[] = $comment;
			if(isset($comment['replies'])){
				$this->traverse($comment['replies'], $level + 1, $output);
			}
		}
	}

}
