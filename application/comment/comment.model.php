<?php

class CommentModel extends Model {

	function getItemComments($item, $item_id, $reverse = false, $limit = 0, $page = 1, $only_accepted = true){
		
		$query = $this->db->from('comment')->select(
			'comment_id', 'content', 'author_name', 'author_url', 'author_email', 'user_id',
			'parent_comment_id', array('UNIX_TIMESTAMP', 'created', 'created')
		)->where('item', $item)->where('item_id', $item_id);
		
		if($only_accepted){
			$query->where('status', 'accepted');
		}
		
		if($limit){
			$list =  $this->getCommentList($item, $item_id, $reverse, $limit, $page, $only_accepted, array(0));
			$in = $list_str ? "AND comment_id IN ($list_str)" : "";
		}

		return $query->fetchTree('comment_id', 'parent_comment_id', 'replies');

	}

	private function getCommentList($item, $item_id, $reverse, $limit, $page, $only_accepted, $search_in){

		$offset = $page > 0 ? $limit*($page-1) : 0;

		$query = $this->db->from('comment')
					->where('item', $item)
					->where('item_id', $item_id)
					->where('parent_comment_id', $search_in);
		
		if($only_accepted){
			$query->where('status', 'accepted');
		}
		
		if($limit){
			$query->limit($limit, $offset);
		}
		
		if($reverse){
			$query->orderby('comment_id', 'DESC');
		}

		$search_in = $query->fetchArray();

		if($search_in){
			return array_merge($search_in, $this->getCommentList($item, $item_id, false, 0, 0, $only_accepted, $search_in));
		}
		else{
			return $search_in;
		}
		
	}
	
	function getBaseCount($item, $item_id){
		return $this->getChildrenCount($item, $item_id, 0);
	}

	function getChildrenCount($item, $item_id, $parent_comment_id){
		return $this->db->from('comment')
				->where('item', $item)
				->where('item_id', $item_id)
				->where('parent_comment_id', $parent_comment_id)
				->fetchScalar();
	}

	function getCount($item, $item_id, $only_accepted = true){
		$query = $this->db->from('comment')->select(array('count','*'))->where('item_id', $item_id)->where('item', $item);
		if($only_accepted){
			$query->where('status', 'accepted');
		}
		return $query->fetchScalar();
	}

}
