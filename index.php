	<?php
	// Remember to copy files from the SDK's src/ directory to a
	// directory in your application on the server, such as php-sdk/
	require_once('includes/facebook.php');
	$config = array(
		'appId' => '271461459585302',
		'secret' => 'e9d5fa39b258219f6a56bdd0a072d6b6',
		'cookie' => true,
	);
	$facebook = new Facebook($config);
	$user_id = $facebook->getUser();
	$facebook = new Facebook($config);
	/*
	Melchor - 327005300648418
	Gaspar - 278542858849325
	Baltazar - 245033622226380
	Reyes Magos - 121110494654748
	Yo - 584517074
	*/
	$user_id = '121110494654748'; /*'584517074';*/
	$atoken = $facebook->getAccessToken();
?>
<html>
	<head>
		<meta charset="iso-8859-1">
	</head>
	<body>
	<?php
	if($user_id) {
	  // We have a user ID, so probably a logged in user.
	  // If not, we'll get an exception, which we handle below.
	  try {
		$id = '121110494654748';
		$user_profile = $facebook->api($id, 'GET'); // Obtiene los datos del perfil del usuario poniendo como argumento el id de la persona/grupo
		$feed = $facebook->api($id.'/feed', 'GET'); // Obtiene un arreglo con los mensajes del muro
		$posts = $feed['data']; //extrae un arreglo con los mensajes individuales de $feed y los guarda en $posts
		$p_next = $feed['paging']['next']; //guarda el time stamp de la pagina siguiente
		$date = new DateTime();
		mkdir($date->format('U'), 0777);
		$fp = fopen($date->format('U')."/results.json", 'w');
		fwrite($fp, json_encode($posts));
		fclose($fp);
		$next = str_replace('https://graph.facebook.com/','/',$p_next);
		$no = 0;
		//while ($next != null){
			$n_page = 0;
		while ($n_page < 10){
			$n_page++;
			$feed2 = $facebook->api($next, 'GET');
			$posts2 = $feed2['data'];
			$p_next = $feed2['paging']['next'];
			$next = str_replace('https://graph.facebook.com/','/',$p_next);
			$fp2 = fopen($date->format('U')."/pagina_".$n_page.".json", 'w');
			fwrite($fp2, json_encode($posts2));
			fclose($fp2);	
			echo "<br>Creada Pagina ".$n_page." ".$next;
		};
		echo "<br><br>Next para empezar de nuevo ".$p_next;
		echo "<br><br>Se corrio hasta la pagina ".$n_page;
	 } catch(FacebookApiException $e) {
		// If the user is logged out, you can have a 
		// user ID even though the access token is invalid.
		// In this case, we'll get an exception, so we'll
		// just ask the user to login again here.
		$login_url = $facebook->getLoginUrl(); 
		echo 'Please <a href="' . $login_url . '">login.</a>';
		error_log($e->getType());
		error_log($e->getMessage());
	  }   
	} else {
	  // No user, print a link for the user to login
	  $login_url = $facebook->getLoginUrl();
	  echo 'Please <a href="' . $login_url . '">login.</a>';
	}
	?>
	<p>Trabajo terminado!</p>
	</body>
	</html>