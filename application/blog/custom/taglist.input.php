<?php

class TagListInput extends InputText{
	
	protected $_scalar = false;
	protected $separator = ',';
	
	function getValue($filtered = true){

		$values = explode($this->separator, $this->_value);

		$tags = array();
		foreach($values as &$value){
			$value = trim($value);
			if($value != ''){
				if($this->_filter && $filtered){
					$value = call_user_func($this->_filter, $value, $this->getName());
				}
				$tags[] = $value;
			}
		}

		$ids = array();
		foreach($tags as $tag){
			$ds = new TableReader('blog_tag');
			$id = $ds->select('blog_tag_id')->where('tag', $tag)->fetchScalar();
			if(!$id){
				$writer = new TableWriter('blog_tag');
				$id = $writer->insert(array('tag' => $tag, 'url_tag' => TextHelper::urlize($tag)));
			}
			$ids[] = $id;
		}

		return $ids;

	}

	function setValue($values){
		if($values){
			$ds = new TableReader('blog_tag');
			$tags = $ds->select('tag')->where('blog_tag_id', $values, 'in')->orderby('tag')->fetchArray();
			$this->_value = implode($this->separator.($this->separator!= ' ' ? ' ':''), $tags);
		}
	}
	
	function setSeparator($sep){
		$this->separator = $sep;
	}

}

