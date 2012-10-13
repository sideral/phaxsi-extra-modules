<?php

class CMSRouter extends Router{

	public function getController($context){

		if(!$context)
			return false;

		$controller = $this->loadController($context);

		if(!$controller){

			$args = array_merge(array( $context->getModule(), $context->getAction()), $context->getArguments());
			$context = new Context('controller', 'cms', 'page', $args);

			if(!$context || !($controller = $this->loadController($context)))
				return false;

		}

		return $controller;
	}

	protected function getErrorContext(){
		return new Context('controller', 'error', 'notfound');
	}

}
