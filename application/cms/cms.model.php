<?php

class CMSModel extends Model{

	function getMeta($page_id, $lang_id){

		$contents = $this->db->from('cms_page')->select('*')->where('page_id', $page_id)
				->join('cms_page_meta', 'page_id')
					->select('*')
					->where('language_id', $lang_id)
					->fetchRow();

		return $contents;

	}

	function getPage($args, $lang_id){
		
		$page = $this->db->from('cms_page')->join('cms_page_meta', 'page_id')->where('language_id', $lang_id)
							->where('url_entry', $args[0])->fetchRow();
		
		return $page;

	}
	
	function getMenu($parent_page_id, $lang_id){
		
		return $this->db->from('cms_page')
						->where('parent_page_id', $parent_page_id)
						->where('in_menu', 1)
						->orderby('sort_value')
					->join('cms_page_text', 'page_id')
						->where('language_id', $lang_id)
					->from('cms_page')
					->join('cms_template', 'template_id')
					->fetchAllRows();
		
	}
	
	function getAdditionalPages($parent_page_id, $lang_id){
		
		return $this->db->from('cms_page')
						->where('parent_page_id', $parent_page_id)
						->where('in_menu', 0)
						->orderby('sort_value')
					->join('cms_page_text', 'page_id')
						->where('language_id', $lang_id)
					->from('cms_page')
					->join('cms_template', 'template_id')
					->fetchAllRows();
		
	}
	
}