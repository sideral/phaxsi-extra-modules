<?php

class CMSController extends Controller{

	function cms_setup(){
		$this->config = array_merge(array('layout' => '/index/index'), $this->config);
		$this->layout = $this->load->layout($this->config['layout']);
	}

	function page(){

		$this->helper->filter->defaults($this->args, array(0 => '', 1 => '', 2 => ''));

		$lang_id = Lang::getCurrent();

		$page = $this->db->Cms->getPage($this->args, $lang_id);

		if(!$page){
			$this->helper->redirect->to($this->plugin->Error->get404());
		}
		
		$view = $this->view = $this->_call('/'.$page['template'], array('page_id' => $page['page_id']));

		if(!$view){
			$this->helper->redirect->to($this->plugin->Error->get404());
		}

		$this->layout->setTitle($page['meta_title']);
		$this->layout->setDescription($page['description']);
		$this->layout->setKeywords($page['keywords']);
		
		$this->layout->view->set('page', $page);

		$this->view->set('page', $page);

	}

	

}