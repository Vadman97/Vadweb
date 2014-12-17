<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	$comment = $_POST["comment"];
	$filename = $_POST["filename"];
	if (!canComment())
	{
		echo "E:COM_PERM<br>";
		echo "LOOKS LIKE YOU NEED TO LOG IN OR REGISTER TO COMMENT!";
	}
	if (commentTimeout($filename))
	{
		echo "E:COM_TIMEOUT<br>";
		echo "LOOKS LIKE YOU COMMENTED TOO MUCH! Please wait...";
	}
	if (!commentSafe($comment))
	{
		echo "E:COM_UNSAFE<br>";
		echo "WOW MAJ0R H4X0R Stop trying to comment dangerous things... change your comment text please!";
	}
	if (submitComment($comment, $filename))
	{
		ob_clean();
		header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
}
?>
