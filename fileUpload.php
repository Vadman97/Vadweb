<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

	//define("DEFAULT_FILE_STORAGE_PATH", "/home/vadwebData/");
	//definition of default storage moved to util

function error($eNum = NULL)
{

}

function rearrange( $arr )
{
	foreach( $arr as $key => $all ){ //key is file param, all is array of params for all files
		foreach( $all as $i => $val ){ //all is array of params for all files, I one param, val is param value
			$new[$i][$key] = $val;    
		}    
	} 
	return $new;
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	if (!canUpload())
	{
		echo "E:UP_PERM<br>";
		echo "LOOKS LIKE YOU NEED TO LOG IN OR REGISTER TO UPLOAD!";
		return false;
	}
	if (uploadingCooldown() !== false)
	{
		echo "E:COOLDOWN<br>";
		echo "You've uploaded too many files recently!!! Please wait for " . uploadingCooldown() . " (H:M:S) before trying again...";
		return false;
	}
	$fileArr = array();
	$onlyOneFile = isset($_FILES["fileSingle"]["name"])?1:0;
	//echo $onlyOneFile . "<br><br>";
	if (!$onlyOneFile)
	{
		$file_ary = rearrange($_FILES['fileMulti']);
		foreach ($file_ary as $file) 
		{
			//echo "Printing file data <br>";
			//print_r($file);
			$upFile = new UploadedFile($file['name'], $file['tmp_name'], $file['size'], $file['error'], $_POST["perm"], $_POST["otherPermData"], NULL); //here also pass POST[perm]\
			//TODO this is broken with permissions and unlisted, sets the same permission for each file...
			array_push($fileArr, $upFile);
		}
	}
	else
	{
		$upFile = new UploadedFile($_FILES["fileSingle"]['name'], $_FILES["fileSingle"]['tmp_name'], $_FILES["fileSingle"]["size"], $_FILES["fileSingle"]['error'], $_POST["perm"], $_POST["otherPermData"], $_POST["fileDesc"]);
		array_push($fileArr, $upFile);
	}

	if (count($fileArr) > Constants::MULTI_FILE_UPLOAD_NUM_LIMIT)
	{
		echo "E:NUM_FILES";
		return false;
	}
	
	$redirect = true;
	foreach ($fileArr as $key => $f)
	{
		//TODO WHEN FILES FAIL IT STILL REDIRECTS
		//TODO Add authentication for canUpload
		//TODO Actually figure out restrictions, who can do what with uploading
		//TODO Add captcha
		$f->validateFileForErrors();

		if (isset($_POST["unlisted"]))
			$f->setUnlisted();

		if ($f->type == File::$types["MOVIE"])
		{
			echo "MOVIE!!! MAY TAKE TIME TO CONVERT";
		}

		$result = $f->storeFile();
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
		/**some sort of file writing here that will store file in separate folder at /var/vadData (or something) and 
		* insert info about file as printed above into mysql
		* MAKE SURE TO HANDLE ERRORS SOMEHOW (maybe as part of UploadedFile class) for custom errors, eg file name too long,
		* multiple dots in file name etc
		*/
	}
	if ($redirect)
	{
		ob_clean();
		header("Location: /files.php?s=t");
	}
	gc_enable();
	
}
?>
