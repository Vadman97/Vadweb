<?php
	ob_clean();
	require_once "util.php";
	$fileName = $_GET["name"];
	if (!canViewFileByName($fileName, VIEWING_MODE)) {
		//echo "<h1 style='color: red; font-family: Comic Sans MS;'> major h4x0r </h1>";
		exit;
	}
	$sql = SQLCon::getSQL();
    //$sql->sQuery("UPDATE Files SET ViewCount = ViewCount + 1 WHERE FilePath='$fileName'");
	$result = $sql->sQuery("SELECT * FROM Files WHERE FilePath = '$fileName'")->fetchAll();

	$completeFilePath = DEFAULT_FILE_STORAGE_PATH . $result[0]["FilePath"];
    $extension = getExtension($result[0]["FilePath"]); 

	if ($result[0]["Type"] == File::$types["PICTURE"])
	{
		//convert all files to jpeg compressed here
		//also if this page has a $_GET["t=int"] then return thumbnail of certain size
		//TODO If the image is smaller than 1 MB already, do not compress
		header("Content-Type: image/jpeg");
		$imageinfo = getimagesize($completeFilePath);
		$specialCompress = true;
		if (isset($_GET["t"]))
			$resize = true;
		if (isset($_GET["r"]))
			$specialCompress = false;
		if (filesize($completeFilePath) < 1000000)
			$specialCompress = false;

		switch ($imageinfo[2])
		{
		  case IMAGETYPE_JPEG : 
		  {
		  	$img = imagecreatefromjpeg($completeFilePath);  
		  	break;
		  }
		  case IMAGETYPE_PNG  : 
		  {
		  	$img = imagecreatefrompng($completeFilePath);
		  	break;
		  }
		  default: $specialCompress=false;
		}
		if ($resize) //for thumbnails
		{
			list($width, $height) = $imageinfo;
			$widthMult = 128 / $width;
			//$heightMult = 256 / $height;
			$newWidth = $width * $widthMult;
			$newHeight = $height * $widthMult;
			if ($width < 128 || $height < 128)
			{
				$newWidth = $width;
				$newHeight = $height;
			}
			$newImg = imagecreatetruecolor($newWidth, $newHeight);
			imagecopyresized($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
			imagejpeg($newImg, NULL, 100);
			imagedestroy($img);
			imagedestroy($newImg);
			exit();
		}
		if ($specialCompress)
		{
			imagejpeg($img, NULL, 50); //passes to stream
			imagedestroy($img);
			logFileView($result[0]["File_ID"], NULL, NULL, FILE_PHP);
			exit();
		}
	}
	if ($result[0]["Type"] == File::$types["FLASH"])
	{
		header("Content-Type: application/x-shockwave-flash");
	}
	if ($result[0]["Type"] == File::$types["AUDIO"]) //AMBIGUOUS NEEDS CLARIFICATION
	{
		header("Content-Type: audio/mpeg");
	}
	if ($result[0]["Type"] == File::$types["MOVIE"]) //AMBIGUOUS NEEDS CLARIFICATION
	{
		if ($extension == "mov")
			header("Content-Type: video/quicktime");
		else if ($extension == "ogg" || $extension == "ogv")
			header("Content-Type: video/ogg");
		else if ($extension == "mpeg")
			header("Content-Type: video/mpeg");
		else if ($extension == "avi")
			header("Content-Type: video/avi");
		else if ($extension == "webm")
			header("Content-Type: video/webm");
		else
			header("Content-Type: video/mp4");
	}
	if ($result[0]["Type"] == File::$types["PDF"])
	{
		header("Content-Type: application/pdf");
	}
	if ($result[0]["Type"] == File::$types["OTHER"])
	{
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header("Content-Type: application/octet-stream");
		header('Content-Disposition: attachment; filename="' . $result[0]["FilePath"] . '"');
	}
	logFileView($result[0]["File_ID"], NULL, NULL, FILE_PHP); //for everything BUT thumbnails
	$fp = fopen($completeFilePath, 'rb');
	header("Content-Length: ".filesize($completeFilePath));
	fpassthru($fp);
	exit();
?>
