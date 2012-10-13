<?php

class CommentController extends Controller {

	function comment_setup(){
		
		$default_config = array(
			'items' => array()
		);
		
		$this->config = array_merge($default_config, $this->config);
		
	}

	function add_process(){
		
		$this->helper->filter->validate($this->args, array(0 => array('in' => $this->config['items'])));

		if(is_null($this->args[0])){
			$this->plugin->Error->goto404();
		}

		$params = array('item_id' => 0);

		$form = $this->load->form('addcomment', $_POST, $params);
		$form->validateOrRedirect();
		
		$values = $form->getTargetValues('comment');
		
		$values['user_id'] = $this->plugin->Auth->get('user_id');
		$values['item'] = $this->args[0];
		$values['author_ip'] = $_SERVER['REMOTE_ADDR'];
		
		$comment_id = $this->db->into('comment')->insert($values);
		
		Session::setFlash('posted', true);

	}

}