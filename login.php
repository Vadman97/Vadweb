<?php
	ob_clean();
	//require_once("htmlHead.php");
	require_once("util.php");
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if (isset($_POST["redirLoc"]))
			$loc = $_POST["redirLoc"];
		else
			$loc = $_GET["redirLoc"];
		
		//sleep(0.25);
		if (login($_POST["username"], $_POST["password"], false))
		{
			if (strpos($loc, "?") === true)
				$loc = $loc . "&logC=t";
			else
				$loc = $loc . "?logC=t";
		}
		else
		{
			if (strpos($loc, "?") === true)
				$loc = $loc . "&logC=f";
			else
				$loc = $loc . "?logC=f";
		}
		if (parse_url($_SERVER["HTTP_REFERER"], PHP_URL_PATH) != "/") {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
			exit;
		}
		
		header("Location: " . $loc);
		exit();
	}
?>
