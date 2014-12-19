<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	$comment = $_POST["comment"];
	$filename = $_POST["filename"];
	if (isset($_POST["subCommentOf"]))
		$superID = $_POST["subCommentOf"];
	else
		$superID = NULL;
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
	$comment = safeComment($comment);
	if (submitComment($comment, $filename, $superID))
	{
		echo "S";
		ob_clean();
		header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
}
?>
