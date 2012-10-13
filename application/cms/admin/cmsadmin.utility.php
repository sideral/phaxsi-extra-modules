<?php

require_once(APPD_APPLICATION .'/cms/classes/cms.class.php');

class CMSAdminUtility extends AdminUtility{
	
	function main(){
		
		$list = $this->components->add('list', array(
			'title' => 'Menu Pages',
			'table'=> 'cms_nav_item',
			'dataset' => $this->db->from('cms_nav_item')->select('nav_item_id')
								->where('in_menu', 1)
								->where('parent_nav_item_id', 0)->orderby('sort_value')
							->join('cms_nav_item_name', 'nav_item_id')
								->select('name')->where('language_id', Lang::getCurrent()),
			'operations' => array(
				'add' => array('cms/page_add', 'Add Page'),
				'edit' => array('cms/page_edit'),
				'delete' => true,
				'order' => 'sort_value'
			),
			'messages' => array(
				'add' => array('The page was added', 'There was an error adding the page'),
				'edit' => array('The page was updated', 'There was an error updating the page'),
				'delete' => array('The page was deleted', 'There was an error deleting the page')
			),			
			'params' => array('in_menu' => 1)
		));
		
	}

	function page_add(){

		$form = $this->components->add('form', array(
			'on_save' => array(&$this, '_putUrlEntry')
		));
		
		$langs = $this->db->from('cms_language')->fetchAllRows();
		
		$page = $form->addTable('cms_page', array(
			'template' => array('type' => 'dropdown', 'validator' => $this->valid->required)
		));
	
		$cms  = new CMS($this->context);		
		$templates = $cms->getTemplateList();
		
		$page->template->setDataSource($templates);
		
		$nav_item = $form->addTable('cms_nav_item', array(
			'in_menu' => array('type' => 'hidden', 'value' => $this->node->arg('in_menu')),
			'sort_value' => array('type' => 'hidden','value' => $this->db->from('cms_nav_item')->select(array('max', 'nav_item_id'))->fetchScalar() + 1),
		));
		
		$nav_item->page_id->setReference($page->page_id);
		
		foreach($langs as $lang){
			$nav_item_name = $form->addTable('cms_nav_item_name', array(
				'name' => array('validator' => $this->valid->required, 'label' => 'Title ('.$lang['name'].')'),
				'language_id' => array('value' => $lang['language_id'])
			));
			$nav_item_name->nav_item_id->setReference($nav_item->nav_item_id);
		}

		foreach($langs as $lang){
			
			$page_meta = $form->addTable('cms_page_meta', array(
				'url_entry' => array(
					'validator' => $this->valid->url_identifier,
					'type' => 'hidden'
				),
				'language_id' => array('value' => $lang['language_id']),
				'title' => array('type' => 'hidden'),
				'meta_title' => array('type' => 'hidden'),
				'description' => array('type' => 'hidden'),
				'keywords' => array('type' => 'hidden')
			));
			
			$page_meta->page_id->setReference($page->page_id);

		}

		
	}
	
	function page_edit(){
		
		$nav_item_id = $this->node->arg('nav_item_id');
		
		$nav_info = $this->db->from('cms_nav_item')->where('nav_item_id', $nav_item_id)
						->join('cms_nav_item_name', 'nav_item_id')
							->where('language_id', Lang::getCurrent())
						->fetchRow();
		
		$page_id = $this->db->from('cms_nav_item')->select('page_id')->where('nav_item_id', $nav_item_id)->fetchScalar();
		
		$this->node->setArgument('page_id', $page_id);
		
		$page_meta = $this->db->from('cms_page')->where('page_id', $page_id)
						->join('cms_page_meta', 'page_id')->where('language_id', Lang::getCurrent())
						->fetchRow();
		
		$cms  = new CMS($this->context);
		
		$config = $cms->getConfig($page_meta['template']);
		if(isset($config['node'])){
			$parts = explode('/', $config['node']);		
			if($parts[0] == ''){array_shift($parts);}
			$this->admin->modules[$parts[0]]->{$parts[1]}();
		}

		
	}
	
	function page_metadata(){
		
		$nav_item_id = $this->node->arg('nav_item_id');
		
		$nav_info = $this->db->from('cms_nav_item')->where('nav_item_id', $nav_item_id)
						->join('cms_nav_item_name', 'nav_item_id')
							->where('language_id', Lang::getCurrent())
						->fetchRow();
		
		$form = $this->components->add('form', array(
			'title' => $nav_info['name'] .' - Metadata',
			'back' => true
		));
		
		$page = $form->addTable('cms_page', array(
			'template' => array('type' => 'hidden')
		));
		
		$values = $this->db->from('cms_page')->where('page_id', $this->node->arg('page_id'))->fetchRow();
		$form->setValues($page, $values);
		
		$langs = $this->db->from('cms_language')->fetchAllRows();

		$texts = array();

		foreach($langs as $lang){
			
			$nav_item = $form->addTable('cms_nav_item_name', array(
				'name' => array('validator' => array('required' => true), 'label' => 'Menu Entry'),
				'language_id' => array('value' => $lang['language_id'])
			));
			
			$values = $this->db->from('cms_nav_item_name')
							->where('nav_item_id', $nav_item_id)
							->where('language_id', $lang['language_id'])
						->fetchRow();

			$form->setValues($nav_item, $values);
			
			$page_meta = $form->addTable('cms_page_meta', array(
				'url_entry' => array('validator' => $this->valid->get('url_identifier', 'required')),
				'language_id' => array('value' => $lang['language_id'])
			));

			$page_meta->page_id->setReference($page->page_id);

			$text = $this->db->from('cms_page_meta')
					->where('page_id', $this->node->arg('page_id'))
					->where('language_id', $lang['language_id'])
					->fetchRow();

			$form->setValues($page_meta, $text);
			
			$texts[$lang['language_id']] = array($nav_item, $page_meta);

		}
		
		$fields = array();

		if(count($langs) > 1){
			/**
			 * Add Language Tabs
			 */
			$tabs = $form->getFormObject()->add('/admin/formtabs', 'tabs');
			$lang_tabs = array();
			foreach($langs as $lang){
				$lang_tabs[] = array($lang['name'], 'lang_'.$lang['language_id']);
			}
			$tabs->addTabs($lang_tabs);
			$fields = array('tabs');
		}

		foreach($langs as $lang){
			$fields['tab_fieldset lang_'.$lang['language_id']] = array(
				$texts[$lang['language_id']][0]->name,
				$texts[$lang['language_id']][1]->url_entry,
				$texts[$lang['language_id']][1]->title, 
				$texts[$lang['language_id']][1]->meta_title, 
				$texts[$lang['language_id']][1]->description,
				$texts[$lang['language_id']][1]->keywords
			);
		}

		$form->fieldset($fields, array('class' => 'tabs'));
		
	}

	function widgets(){
		
		
		
	}
	
	function _putUrlEntry($values){
		
		foreach($values['cms_page_meta'] as $i => $cms_page_meta){
			$title = $values['cms_nav_item_name'][$i][0]['name'];
			$this->db->into('cms_page_meta')
				->where('language_id', $cms_page_meta[0]['language_id'])
				->where('page_id', $cms_page_meta[0]['page_id'])
				->update(array(
					'url_entry' => TextHelper::urlize($title),
					'title' => $title
				));
		}
		
	}

}