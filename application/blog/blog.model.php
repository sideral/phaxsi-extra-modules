<?php

class BlogModel extends Model{

	function getPagePosts($blog_id, $limit = 0, $offset = 0){

		$posts =  $this->db->from('blog_post')
					->select('*', array('unix_timestamp', 'pub_date', 'pub_date'))
						->where('blog_id', $blog_id)
						->where('status', 'published')
						->where('enabled', 1)
					->orderby('pub_date', 'DESC')
					->join('blog_author', 'blog_author_id')
						->select('name AS author_name')
					->from('blog_post')
					->join('blog_category', 'blog_category_id')
						->select('name AS category_name')
					->from('blog_post')
					->join('blog_post_image','blog_post_id')
						->select('filename AS image');

		if($limit){
			$posts->limit($limit, $offset);
		}

		$rows = $posts->fetchAllRows();

		foreach($rows as &$post){
			$post['tags'] = $this->getPostTags($post['blog_post_id']);
			$post['comment_count'] = $this->db->Comment->getCount('blog_post',$post['blog_post_id']);
		}

		return $rows;

	}

	function getPost($blog_post_id){

		$post =  $this->db->from('blog_post')
					->select('*', array('unix_timestamp', 'pub_date', 'pub_date'))
					->where('blog_post_id', $blog_post_id)
					->where('status', 'published')
					->join('blog_author', 'blog_author_id')
						->select('name AS author_name')
						->from('blog_post')
					->join('blog_category', 'blog_category_id')
						->select('name AS category_name')
					->from('blog_post')
					->join('blog_post_image','blog_post_id')
						->select('filename AS image')
					->fetchRow();

		$post['tags'] = $this->getPostTags($post['blog_post_id']);
		$post['comment_count'] = $this->db->Comment->getCount('blog_postt',$post['blog_post_id']);

		return $post;

	}
	
	//Should return rows?
	function getPostTags($post_id){
		$tags = $this->db->from('blog_post_tag')
					->where('blog_post_id', $post_id)
					->join('blog_tag', 'blog_tag_id')
					->select('tag')
					->fetchArray();
		return $tags;
	}
	
	function getCategoryTags($category_id, $only_enabled = false, $only_published = false){
		
		$result = $this->query(
			'SELECT bt.blog_tag_id, bt.tag, bt.url_tag, COUNT(*) AS tag_count
				FROM blog_tag bt
				INNER JOIN blog_post_tag bpt
					ON bpt.blog_tag_id = bt.blog_tag_id
				INNER JOIN blog_post bp
					ON bp.blog_post_id = bpt.blog_post_id
				WHERE bp.blog_category_id = ? '
					. ($only_enabled ? 'AND bp.enabled = 1 ' : '') 
					. ($only_published ? 'AND bp.status = "published" ' : '').
			'GROUP BY bt.blog_tag_id
			ORDER BY bt.tag ASC', $category_id
		);
		
		return $result->fetchAllRows();
		
	}

	function search($blog_id, $q, $limit = 5, $offset = 0){
		
		$result = $this->query(
					"SELECT bp.*, UNIX_TIMESTAMP(bp.pub_date) AS pub_date, ba.name AS blog_author
						FROM blog_post bp
							INNER JOIN blog_author ba ON bp.blog_author_id = ba.blog_author_id
						WHERE bp.blog_id = ?
							AND bp.status = 'published'
							AND MATCH(bp.title, bp.content) AGAINST (?)
						ORDER BY bp.pub_date DESC", $blog_id, $q);

		$rows =  $result->fetchAllRows();

		$post['tags'] = array();

		foreach($rows as &$post){
			$post['tags'] = $this->getPostTags($post['blog_post_id']);
			$post['comment_count'] = $this->db->Comment->getCount('blog_post',$post['blog_post_id']);
		}

		return $rows;

	}

	function getPostsByTag($blog_id, $tag, $limit = 0, $offset = 0){

		$posts =  $this->db->from('blog_post')
					->select('*', array('unix_timestamp', 'pub_date', 'pub_date'))
						->where('blog_id', $blog_id)
						->where('status', 'published')
						->where('enabled', 1)
						->orderby('pub_date', 'DESC')
					->join('blog_author', 'blog_author_id')
						->select('name AS author_name')
					->from('blog_post')
					->join('blog_post_tag', 'blog_post_id')
					->join('blog_tag', 'blog_tag_id')
						->where('tag', $tag)
					->from('blog_post')
						->join('blog_category', 'blog_category_id')
						->select('name AS category_name')
					->from('blog_post')
					->join('blog_post_image','blog_post_id')
						->select('filename AS image');;

		if($limit){
			$posts->limit($limit, $offset);
		}

		$rows =  $posts->fetchAllRows();

		foreach($rows as &$post){
			$post['tags'] = $this->getPostTags($post['blog_post_id']);
			$post['comment_count'] = $this->db->Comment->getCount('blog_post',$post['blog_post_id']);
		}

		return $rows;

	}

	function getAllTags(){

		$tags = $this->db->from('blog_tag')->select('tag')
						->orderby('tag', 'asc')
						->fetchArray();

		return $tags;

	}

	function getArchiveDates($blog_id){

		$max = $this->db->from('blog_post')->select(array('max', 'pub_date'))->where('blog_id', $blog_id)->fetchScalar();
		$min = $this->db->from('blog_post')->select(array('min', 'pub_date'))->where('blog_id', $blog_id)->fetchScalar();

		$months = array();

		if($max){
			$max_date = explode('-',$max);
			$min_date = explode('-', $min);

			$month_name = array(1=>'January', 2=>'February', 3=>'March', 4=>'April', 5=>'May',
						6=>'June', 7=>'July', 8=>'August', 9=>'September', 10=>'October',
						11=>'November', 12=> 'December');

			for($year = $min_date[0]; $year <= $max_date[0] ; $year++){
				for($month = (int)$min_date[1]; $month <= 12; $month++){
					if($year != $max_date[0] || $month <= $max_date[1]){
						$months[$year.'-'.$month] = $month_name[$month].' '.$year;
					}
				}
			}
		}

		return $months;

	}

	function getBlogInfo($blog_id){
		return $this->query('SELECT * FROM blog WHERE blog_id = ?', $blog_id)->fetchRow();
	}

	function getCategories($blog_id){
		
		return $this->db->from('blog_category')->where('blog_id', $blog_id)->fetchAllRows();
		
	}
	
}
