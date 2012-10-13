<?php

class BlogController extends Controller{

	protected $blog_id = 1;
	protected $blog_config = array();
	protected $util = null;

	function blog_setup(){

		$this->blog_config = $this->db->Blog->getBlogInfo($this->blog_id);

		if($this->view instanceof HtmlView){
			$this->view->setTemplate('themes/'.$this->blog_config['theme'].'/blog');
			$this->util = $this->view->set('util', $this->load->utility('blog'));
			$this->util->blog_config = $this->blog_config;
			$this->view->set('single_post', false);
			$this->view->set('tag', false);
		}

	}

	function blog(){
		$this->layout->setTitle($this->blog_config['page_title'] .' - '. $this->blog_config['description'], false);
		$this->view->set('posts', $this->db->Blog->getPagePosts($this->blog_id, $this->blog_config['posts_per_page']));
	}

	function post(){

		$this->util->post_page = $this->view->set('single_post', true);

		$this->helper->filter->defaults($this->args, array(0 => ''), array(0 => 'url_title'));
		$posts = $this->view->set('posts', array($this->db->Blog->getPost($this->args['url_title'])));

		$this->layout->setTitle($posts[0]['title']);

		$this->view->set('comments',
			$this->load->block('/comment/comments',	array(
				'item' => 'blog_post', 'item_id' => $posts[0]['blog_post_id'],
				'template' => '/blog/themes/'.$this->blog_config['theme'].'/comments',
				'title' => $posts[0]['title']
			))
		);

		$this->view->set('comment_form',
			$this->load->block(
				'/comment/commentform',
				array('item' => 'blog_post', 'item_id' => $posts[0]['blog_post_id'],
					  'template' => '/blog/themes/'.$this->blog_config['theme'].'/commentform')
			)
		);

	}

	function search(){
		$this->helper->filter->defaults($this->args, array('q' => ''));
		$posts = $this->db->Blog->search($this->blog_id, $this->args['q']);
		$this->view->set('posts',$posts );
	}

	function tags(){
		$this->helper->filter->defaults($this->args, array(0 => ''));
		$this->view->set('posts',$this->db->Blog->getPostsByTag($this->blog_id, $this->args[0]));
		$this->view->set('tag', $this->args[0]);
	}

	function archive(){

	}

	function rss_feed(){
		
		$this->helper->filter->validate($this->args, array(0 => array('db_in_column' => array('blog', 'blog_id'))));

		if(is_null($this->args[0])){
			$this->plugin->Error->goto404();
		}
		
		$blog = $this->db->Blog->getBlogInfo($this->args[0]);
		
		$feed = $this->view->getFeed();

		$feed->setTitle($blog['name']);
		$feed->setLink(UrlHelper::get('/blog/rss_feed'));
		$feed->setDescription($blog['description']);

		$feeds = $this->db->from('blog_post')->where('blog_id', $this->args[0])->where('status', 'published')->orderby('pub_date', 'desc')->limit(50)->fetchAllRows();

		foreach($feeds as $post){

			$item = $feed->createNewItem();

			//Add elements to the feed item
			$item->setTitle($post['title']);
			$item->setLink(UrlHelper::get('/blog/post/'.$post['url_title']));
			$item->setDate($post['pub_date']);
			$item->setDescription($post['content']);

			//Now add the feed item
			$feed->addItem($item);

		}


	}


}

