<?php

class BlogUtility extends Utility{

	public $config = null;
	public $post_page = false;

	function tagsToHtml($tags){

		$html = $this->load->helper('html');
		$text = $this->load->helper('text');

		$output = array();

		foreach($tags as $tag){
			$url_tag = $text->urlize($tag);
			$output[] = $html->link($tag, 'tags/'.$url_tag);
		}

		return implode(', ', $output);

	}

	function summary($post, $next = 'Continue reading >',  $hide_before = false){

		$content = $post['content'];

		if(!$this->config['show_summary']){
			return $content;
		}

		$pattern = '<p><!-- Break --></p>';
		$pos =  strpos($content, $pattern);

		if($pos !== false){
			if(!$this->post_page){
				$html = $this->load->helper('html');
				$content = substr($content, 0, $pos) . $html->link($next, 'post/'.$post['url_title']);
			}
			else if($hide_before){
				$content = substr($content, $pos + strlen($pattern));
			}
		}

		return $content;
		
		
	}

	public function timeAgo($timestamp){
		return DateTimeHelper::timeAgoString($timestamp, 1) . ' ago';
	}

}