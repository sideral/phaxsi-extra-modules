<?php

abstract class CMSTemplateUtility extends Utility{
	
	protected $widgets = array();
	/**
	 *
	 * @var CMS
	 */
	protected $cms = null;
	
	final function initialize(CMS $cms){
		$this->cms = $cms;		
	}
	
	abstract function getTemplateList();
	
}