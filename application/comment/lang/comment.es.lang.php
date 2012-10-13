<?php

class CommentLang extends Lang{

	public $comment_errors = array('required' => "El comentario es requerido.");

	public $no_comments = "No hay comentarios aún.";

	public function timeAgo($timestamp){
		return 'Añadido hace '. DateTimeHelper::timeAgoString($timestamp, 1);
	}
	
	public $edit = 'Editar Comentario';

	public $comment = 'Comentario';
	public $email = 'Email';
	public $website = 'Sitio Web';
	public $name = 'Nombre';
	public $status = 'Status';
	
	public $messages = array(
		'email' => array(
			'required' => 'El email es requerido',
			'expression' => 'El email no es válido'
		),
		'name' => array(
			'required' => 'El nombre es requerido'
		)
	);
	
	public $fields = array(
		'content' => 'Contenido',
		'author_name' => 'Nombre del Autor',
		'author_email' => 'Email del Autor',
		'author_url' => 'URL del Autor',
		'author_ip' => 'IP del Autor'
	);
	
	public $status_types = array(
		'pending' => 'Pendiente',
		'accepted' => 'Aceptado',
		'rejected' => 'Rechazado'
	);

}

