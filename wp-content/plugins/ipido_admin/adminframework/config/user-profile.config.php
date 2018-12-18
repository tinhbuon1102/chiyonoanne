<?php
$options = array(
	array(
		'id'     => 'csf_user_meta',
		'title'  => 'Información de Usuario',
		'fields' => array(
			array(
				'type'     => 'text',
				'title'    => 'RUT',
				'validate' => 'required',
				'id'       => 'user_rut',
			),
			array(
				'type'  => 'switcher',
				'title' => 'Estado de la cuenta',
				'id'    => 'user_account',
			),
		),
	),
);

// new CSFramework_User_Profile($options);