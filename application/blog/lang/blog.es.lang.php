<?php

class BlogLang extends Lang{

	public $fields = array(
		'title' => 'Título',
		'pub_date' => 'Fecha de Publicación',
		'pub_month' => 'Mes de Publicación',
		'content' => 'Contenido',
		'summary' => 'Resumen',
		'status' => 'Status',
		'enabled' => 'Habilitado',
		'tags' => 'Tags',
		'separated' => 'Separados por comas',
		'author' => 'Autor',
		'category' => 'Categoría',
		'name' => 'Nombre'
	);
	
	public $posts = array(
		'blog_posts' => 'Entradas',
		'tag' => 'Tag',
		'new_post' => 'Nueva Entrada',
		'messages' => array(
			'add' => array('La entrada fue añadida', 'Hubo un error añadiendo la entrada'),
			'edit' => array('La entrada fue actualizada', 'Hubo un error actualizando la entrada'),
			'delete' => array('La entrada fue eliminada', 'Hubo un error eliminando la entrada')
		)
	);
	
	public $categories = array(
		'categories' => 'Categorías',
		'add' => 'Añadir Categoría',
		'select' => '- Seleccionar -'
	);
	
	public $tags = array(
		'tags' => 'Tags',
		'edit' => 'Editar Tag'
	);
	
	public $status = array(
		'' => '- Seleccionar -',
		'published' => 'Publicado',
		'draft' => 'Borrador'
	);
	
	public $months = array( 1=>'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre','Octubre', 'Noviembre', 'Diciembre');
	

}