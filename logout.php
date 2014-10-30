<?php
	session_start();
	$_SESSION['loggedInUsername'] = NULL;
	$_SESSION['cachedUserGroup'] = NULL;
	ob_clean();
	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>