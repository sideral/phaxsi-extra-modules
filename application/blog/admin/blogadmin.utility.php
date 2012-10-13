<?php

class BlogAdminUtility extends AdminUtility{

	function posts(){
		
		$blog_id = $this->node->arg('blog_id');

		$dataset = $this->db->from('blog_post')
						->select('blog_post_id', 'title')
							->orderby('pub_date', 'desc')
						->join('blog_author', 'blog_author_id')
							->select('name');
		
		if($blog_id){
			$dataset = $dataset->from('blog_post')->where('blog_id', $blog_id);
		}
		
		if($this->node->arg('blog_author_id')){
			$dataset = $dataset->where('blog_author_id', $this->node->arg('blog_author_id'));
		}

		$list = $this->components->add('list', array(
			'title' => $this->lang->posts['blog_posts'],
			'table' => 'blog_post',
			'dataset' => $dataset,
			'operations' => array(
				'add' => array('blog/post', $this->lang->posts['new_post']),
				'edit' => array("blog/post"),
				'delete' => true,
				'explore' => array(&$this, 'blog_explore')
			),
			'pagination' => array('auto' => true),
			'messages'   => $this->lang->posts['messages']
		));
		
		$list->addFilter('text', array('blog_post', 'title'), $this->lang->fields['title']);
		
		$list->addFilter('text', array('blog_author', 'name'), $this->lang->fields['author']);
		
		$list->addFilter('dropdown', array('blog_post', 'blog_category_id'), $this->lang->fields['category'], $this->db->from('blog_category')->select('blog_category_id', 'name'));
		
		if($this->node->arg('blog_post-blog_category_id')){
			
			$cat_tags = $this->db->Blog->getCategoryTags($this->node->arg('blog_post-blog_category_id'));
			$cat_tags_table = array();
			
			foreach($cat_tags as $cat_tag){
				$cat_tags_table[$cat_tag['blog_tag_id']] = $cat_tag['tag'];		
			}
			
			$list->addFilter('dropdown', 'filter_tag', $this->lang->posts['tag'], $cat_tags_table);

			if($this->node->arg('filter_tag')){
				$dataset = $list->getDataset()->from('blog_post')->join('blog_post_tag', 'blog_post_id')
								->where('blog_tag_id', $this->node->arg('filter_tag'));
				$list->setDataset($dataset);
			}
		}
		
		$oldest_post_date = $this->db->from('blog_post')->select(array('DATE_FORMAT', array('pub_date', '"%Y-%m"')))->orderby('pub_date', 'asc')->limit(1)->fetchScalar();
		
		if(!$oldest_post_date){
			$oldest_post_date = date('Y-n');
		}
		
		$meses = $this->getMonthList($oldest_post_date, date('Y-n'));
			
		$list->addFilter('dropdown', 'filter-pub_date', $this->lang->fields['pub_month'], $meses);
		
		if($this->node->arg('filter-pub_date')){
			list($year, $month) = explode('-', $this->node->arg('filter-pub_date'));
			$dataset = $list->getDataset()->from('blog_post')
						->where(array('month', 'pub_date'), $month)
						->where(array('year', 'pub_date'), $year);
			$list->setDataset($dataset);
		}
		
		array_shift($this->lang->status);
		
		$list->addFilter('dropdown', array('blog_post', 'status'), $this->lang->fields['status'], $this->lang->status);
				
	}

	function post(){

		$blog_post_id = $this->node->arg('blog_post_id');
		$values = array();

		$form = $this->components->add('form',
			array('back' => $this->db->from('blog_post')->select('title'))
		);

		$author_id =  $this->db->from('blog_author')
								->select('blog_author_id')
								->where('user_id', $this->plugin->Auth->get('user_id'))
								->fetchScalar();

		$blog_post = $form->addTable('blog_post',array(
			'title'			=> array('validator' => $this->valid->get('required', 'no_html')),
			'content'			=> array('validator' => $this->valid->required),
			'blog_author_id'	=> array('value' => $author_id),
			'blog_id'			=> array('value' => $this->node->arg('blog_id')),
			'pub_date'			=> array('value' => date('Y-m-d H:i:s')),
			'summary'			=> array('validator' => $this->valid->required),
			'status'			=> array('validator' => $this->valid->required),
			'blog_category_id'	=> array('type' => 'dropdown', 'validator' => $this->valid->required)
		));

		$plugins = $blog_post->content->getConfigOption('plugins');
		
		$blog_post->content->setConfigOption('plugins', $plugins.',table,pagebreak,markettoimages');
		$blog_post->content->setConfigOption('pagebreak_separator','<!-- Break -->');
		$blog_post->content->setConfigOption('height', 600);
		$blog_post->content->setConfigOption('theme_advanced_blockformats', "p,pre,h1,h2,h3,h4,blockquote,code");
		$blog_post->content->setConfigOption('content_css', UrlHelper::resource('/blog/tinymce.css'));
		$blog_post->content->setConfigOption('theme_advanced_buttons1_add', 'pagebreak,markettoimages');
		$blog_post->content->setConfigOption('theme_advanced_buttons2', 'tablecontrols');
		$blog_post->content->setConfigOption('valid_elements', '*[*]');
		$blog_post->content->filter_html = false;
		
		
		$blog_post->blog_category_id->setDataSource($this->db->from('blog_category')->select('blog_category_id', 'name')->where('blog_id', $this->node->arg('blog_id'))->orderby('name', 'asc'));
		$blog_post->blog_category_id->add('', $this->lang->categories['select']);
		
		$blog_post->status->setDataSource($this->lang->status);

		$blog_post_tag = $form->addTable('blog_post_tag',
			array('blog_tag_id'	 => array('type' => '/blog/taglist')),
			array('keys' => array('blog_post_id' => $blog_post ),
				  'delete' => array('blog_post_id' => $blog_post),
			      'multirow' => true,
				  'multicolumn' => true)
		);
		
		$blog_post_tag->blog_post_id->setReference($blog_post->blog_post_id, true);
		
		$blog_post_tag->blog_tag_id->setNote($this->lang->fields['separated']);
		
		if($blog_post_id){

			$values = $this->db->from('blog_post')->where('blog_post_id', $blog_post_id)->fetchRow();
			$form->setValues($blog_post, $values);

			$values = $this->db->from('blog_post_tag')
												->select('blog_tag_id')
												->where('blog_post_id', $blog_post_id)
												->fetchArray();
			$form->setValues($blog_post_tag, array('blog_tag_id' => $values));

		}

	}

	function blog_explore(){
		$args = $this->env->getArguments();
		$post_id = $args['blog_post_id'];
		$url_title = $this->db->from('blog_post')->select('url_title')
									->where('blog_post_id', $post_id)->fetchScalar();
		$this->load->helper('redirect')->to('/blog/post/'.$url_title);
	}


	function author(){

		$this->components->add('list', array(
			'title' => 'Blog Authors',
			'table' => 'blog_author',
			'dataset' => $this->db->from('blog_author')->select('blog_author_id', 'name'),
			'operations' => array(
				'add' => array('blog/author_save', 'Nuevo Autor'),
				'edit' => array("blog/author_save"),
				'delete' => true
			),
			'messages'   => array(
				'add' => array('Author was added', 'Author was not added'),
				'delete' => array('Author was deleted', 'Author was NOT deleted'))
			)
		);

	}

	function author_save(){

		$form = $this->components->add('form',	array('fill' => 'blog_author'));

		$form->addTable('blog_author',
				array('user_id' => array('type' => 'dropdown',
											  'datasource' => $this->db->from('user')
															->select('user_id', 'username'),
											  'validator' => $this->valid->get('required', 'integer')),
					  'name'	=> array('validator' => $this->valid->get('required', 'no_html'))
				)
		);

	}

	function tags(){

		$tags = $this->db->from('blog_tag')->select('blog_tag_id', 'tag')
						->orderby('tag', 'asc')
						->fetchAllRowsNum();

		foreach($tags as &$tag){
			$tag[2] = ' ('.$this->db->from('blog_post_tag')->where('blog_tag_id', $tag[0])->count() .')';
		}

		$this->components->add('list',array(
			'table' => 'blog_tag',
			'title' => $this->lang->tags['tags'],
			'dataset' => $tags,
			'operations' => array('edit' => array('blog/tag_edit', $this->lang->tags['edit']),'delete' => true))
		);

	}

	function tag_edit(){

		$form = $this->components->add('form');

		$blog_tag = $form->addTable('blog_tag');
		
		$values = $this->db->from('blog_tag')->where('blog_tag_id', $this->node->arg('blog_tag_id'))->fetchRow();
		$form->setValues($blog_tag, $values);

	}
	
	function categories(){
		
		$blog_id = $this->node->arg('blog_id');
		
		$this->components->add('list', array(
			'title' => $this->lang->categories['categories'],
			'table' => 'blog_category',
			'dataset' => $this->db->from('blog_category')->select('blog_category_id', 'name')->orderby('name', 'asc')->where('blog_id', $blog_id),
			'operations' => array(
				'add' => array('blog/category_edit', $this->lang->categories['add']),
				'edit' => array('blog/category_edit'),
				'delete' => true
			)
		));
		
	}
	
	function category_edit(){
		
		$blog_id = $this->node->arg('blog_id');
		
		$form = $this->components->add('form');
		
		$blog_category = $form->addTable('blog_category', array(
			'blog_id' => array('value' => $blog_id)
		));
		
		if($this->node->arg('blog_category_id')){
			$values = $this->db->from('blog_category')->where('blog_category_id', $this->node->arg('blog_category_id'))->fetchRow();
			$form->setValues($blog_category, $values);
		}
		
	}

	private function getMonthList($starting_date, $ending_date){
		
		list($starting_year, $starting_month) = explode('-', $starting_date);
		list($ending_year, $ending_month) = explode('-', $ending_date);		

		$month_list = array();	
		
		for($i = $starting_year; $i <= $ending_year; $i++){
			for($j = ($i == $starting_year ? $starting_month: 1 ); $j <= ($i != $ending_year ? 12: $ending_month ); $j++){
				$month_list[$i.'-'.$j] = $this->lang->months[(int)$j].' '. $i;
			}
		}
		
		return $month_list;
		
	}
	
	
}


