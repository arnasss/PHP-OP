<?php

require '../bootloader.php';

if (isset($_SESSION['logged_in_user'])) {
	header('Location: index.php');
}

$form = [
	'title' => 'Prisijungti',
	'fields' => [
		'email' => [
			'type' => 'text',
			'label' => 'Email:',
			'extra' => [
				'attr' => [
					'placeholder' => 'email@email.com'
				]
			],
			'validators' => [
				'validate_not_empty',
				'validate_email_exists'
			]
		],
		'password' => [
			'type' => 'password',
			'label' => 'New password:',
			'extra' => [
				'attr' => [
					'placeholder' => 'slaptažodis'
				]
			],
			'validators' => [
				'validate_not_empty'
			]
		]
	],
	'buttons' => [
		'login' => [
			'type' => 'submit',
			'class' => 'button'
		]
	],
	'validators' => [
		'validate_login'
	],
	'callbacks' => [
		'success' => 'form_success',
		'fail' => 'form_fail'
	]
];

function form_fail($filtered_input, &$form) {
	unset($form['fields']['password']['value']);
}

function form_success($filtered_input, $form) {
	$modelUser = new \App\Users\Model();
	$_SESSION['logged_in_user'] = $modelUser->get($filtered_input)[0];
	
	header('Location: index.php');
}

$filtered_input = get_filtered_input($form);

if (!empty($filtered_input)) {
	validate_form($filtered_input, $form);
}

$navView = new App\Navigation();
$formView = new Core\View($form);

?>
<html>
    <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Login</title>
		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<!--Require navigation-->
		<?php print $navView->render(ROOT . '/app/templates/navigation.tpl.php'); ?>
		
		<!--Require form template-->
		<?php print $formView->render(ROOT . '/core/templates/form.tpl.php'); ?>
		<div class="wrapper">
			<p>Arba <a href="register.php">registruotis!</a></p>
		</div>
    </body>
</html>