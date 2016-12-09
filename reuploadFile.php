<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

function error($eNum = NULL)
{

}


if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	if (!canUpload())
	{
		echo "E:UP_PERM<br>";
		echo "LOOKS LIKE YOU NEED TO LOG IN OR REGISTER TO UPLOAD!";
		return false;
	}
	
	if (!isFileOwner($_POST["fileID"])) 
	{
		echo "E:NOT_OWNER<br>";
		echo "YOU CAN'T MODIFY FILES THAT AREN'T YOURS!";
		return false;
	}

	$fileData = getFileInfo($_POST["fileID"]);

	$f = new UploadedFile($fileData["FilePath"], $_FILES["fileSingle"]['tmp_name'], $_FILES["fileSingle"]["size"], $_FILES["fileSingle"]['error'], NULL, NULL, NULL);
	
	$redirect = true;
	
	$f->validateFileForErrors();

	if (isset($_POST["unlisted"]))
		$f->setUnlisted();

	if ($f->type == File::$types["MOVIE"])
	{
		echo "MOVIE!!! MAY TAKE TIME TO CONVERT";
	}

	$result = $f->storeFile(true);
	updateFile($_POST["fileID"]); //TODO Make way to change file name

	if ($result !== true)
		$redirect = false;

	//header("Refresh:30; URL=http://www.vadweb.us/files.php");
	echo $result."<br>";
	echo "<h1> ERROR!!! </h1>";	
	echo "Printing info for " . $key . ": <br>";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $f->name . "<br>";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $f->nameNoEXT . "<br>";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $f->extension . "<br>";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp<h2> Please note this error code: " . $f->uploadError . "</h2><br>";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $f->type . "<br>";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $f->size . "<br>";
	echo "&nbsp&nbsp&nbsp&nbsp&nbsp" . $f->absPath . "<br>";
	echo "<br>";
	if ($result > 0) {
		echo "<strong> Successfully wrote to folder file number: " . $key . "</strong><br>";
		echo "<a href='files.php'> Return to files page </a>";
	}
	
	if ($redirect)
	{
		ob_clean();
		header("Location: /files.php?s=t");
	}
	gc_enable();
	
}
?>
