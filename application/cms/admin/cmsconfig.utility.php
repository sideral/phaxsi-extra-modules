<?php 

class CmsConfigUtility extends AdminConfigUtility{
	
	function configureSchema($schema){
		
		$cms_language = $schema->addTable('cms_language',
			 array(
				'language_id' => array('primary', 'language_id'), 
				'name' => array('varchar', 'name') 
			)
		);
		
	
		$cms_nav_item = $schema->addTable('cms_nav_item',
			 array(
				'nav_item_id' => array('primary', 'nav_item_id'), 
				'page_id' => array('key', 'page_id'), 
				'parent_nav_item_id' => array('key', 'parent_nav_item_id'), 
				'sort_value' => array('double', 'sort_value'), 
				'in_menu' => array('bool', 'in_menu') 
			)
		);
		
		$cms_nav_item->page_id->references('cms_page', 'page_id', AdminTable::ON_DELETE_CASCADE);
		$cms_nav_item->parent_nav_item_id->references('cms_nav_item', 'nav_item_id', AdminTable::ON_DELETE_SET_NULL);
	
		
		$cms_nav_item_name = $schema->addTable('cms_nav_item_name',
			 array(
				'nav_item_id' => array('primary', 'nav_item_id'), 
				'language_id' => array('primary', 'language_id'), 
				'name' => array('varchar', 'name') 
					
			)
		);
		
		$cms_nav_item_name->nav_item_id->references('cms_nav_item', 'nav_item_id', AdminTable::ON_DELETE_CASCADE );
		$cms_nav_item_name->language_id->references('cms_language', 'language_id');
	
		$cms_page = $schema->addTable('cms_page',
			 array(
				'page_id' => array('primary', 'page_id'), 
				'template' => array('varchar', 'Template') 
			)
		);

		$cms_page_meta = $schema->addTable('cms_page_meta',
			 array(
				'page_meta_id' => array('primary', 'page_meta_id'), 
				'page_id' => array('key', 'page_id'), 
				'language_id' => array('key', 'language_id'), 
				'url_entry' => array('varchar', 'Url Entry'), 
				'title' => array('varchar', 'Title'), 
				'meta_title' => array('varchar', 'Meta Title'), 
				'description' => array('varchar', 'Meta Description'), 
				'keywords' => array('varchar', 'Meta Keywords'), 
				'date_modified' => array('timestamp', 'date_modified') 
			)
		);
		
		$cms_page_meta->page_id->references('cms_page', 'page_id', AdminTable::ON_DELETE_CASCADE);
		$cms_page_meta->language_id->references('cms_language', 'language_id');
	
		$cms_widget = $schema->addTable('cms_widget',
			 array(
				'widget_id' => array('primary', 'widget_id'), 
				'widget_type' => array('varchar', 'widget_type'), 
				'page_id' => array('key', 'page_id') 
			)
		);
		
		$cms_widget->page_id->references('cms_page', 'page_id', AdminTable::ON_DELETE_CASCADE);
	
		

	}	

	function configureMenu($menu) {
		
		$menu->addEntry(new AdminMenuEntry('Website Content'), array(
			new AdminMenuEntry('Manage Pages', 'cms/main'),
			new AdminMenuEntry('Global Widgets', 'cms/widgets')
		));
		
	}
	
	
}
