<?php
	function generateRandomString($length = 10) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	function generateRandomLetterString($length = 10) 
	{
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	session_start();
	ob_clean();
	
	if ($_GET["d"] == "y")
		unset($_SESSION['salt']);
	if (!isset($_SESSION['salt']))
		$_SESSION['salt'] = generateRandomLetterString(1024);//rand(1, 99999999999);
	echo $_SESSION['salt'];
	session_write_close();
	//unset($_SESSION['salt']);
?>