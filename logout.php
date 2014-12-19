<?php
	require_once("dbcon.php");
	//$_SESSION[] = array();
	// get session parameters 

	$_SESSION = array();

	$params = session_get_cookie_params();
	 
	// Delete the actual cookie. 
	setcookie(session_name(),
	        '', time() - 42000, 
	        $params["path"], 
	        $params["domain"], 
	        $params["secure"], 
	        $params["httponly"]);
	 
	// Destroy session 
	session_destroy();
	ob_clean();
	header("Location: " . $_SERVER["HTTP_REFERER"]);
	exit();
?>