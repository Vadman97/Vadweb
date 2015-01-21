<?php
	require_once "util.php";
	ob_clean();
	if (is_session_started() === FALSE) 
		session_start();
	//incrementPerfCount(session_status());
	$fileName = $_GET["name"];

	if (!canViewFileByName($fileName, VIEWING_MODE)) {
		//echo "<h1 style='color: red; font-family: Comic Sans MS;'> major h4x0r </h1>";
		exit();
	}
	session_write_close();

	$sql = SQLCon::getSQL();
	$stmt = $sql->prepStmt("SELECT FilePath, File_ID, Type FROM Files WHERE FilePath = :file");
 	$sql->bindParam($stmt, ":file", $fileName);
   	$result = $sql->execute($stmt);
   	if (!$result)
		exit();
  	$result = $result->fetchAll();
    
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
		if ($imageinfo[0] == 0 || $imageinfo[1] == 0)
		{
			$specialCompress = false;
			$resize = false;
		}

		if ($resize) //for thumbnails
		{
			$completeCachedThumbnailPath = DEFAULT_FILE_STORAGE_PATH . "thumbnails/" . $result[0]["FilePath"];
			if (thumbnailCached($result[0]["File_ID"]))
			{
				$thumb = imagecreatefromjpeg($completeCachedThumbnailPath); 
				if ($thumb === false)
					exit();
				imagejpeg($thumb, NULL, 100);
				imagedestroy($thumb);
				exit();
			}
			else
			{
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
				list($width, $height) = $imageinfo;
				$desiredRes = 512;
				$widthMult = $desiredRes / $width;
				//$heightMult = 256 / $height;
				$newWidth = $width * $widthMult;
				$newHeight = $height * $widthMult;
				if ($width < $desiredRes)
				{
					$newWidth = $width;
					$newHeight = $height;
				}
				$newImg = imagecreatetruecolor($newWidth, $newHeight);
				imagefill($newImg, 0, 0, imagecolorallocate($newImg, 255, 255, 255));
				imagealphablending($newImg, true);
				imagecopyresized($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
				imagejpeg($newImg, $completeCachedThumbnailPath, 20);
				imagejpeg($newImg, NULL, 20);
				imagedestroy($img);
				imagedestroy($newImg);
				setThumbnailCached($result[0]["File_ID"]);
				exit();
			}
		}
		if ($specialCompress)
		{
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
			$bg = imagecreatetruecolor(imagesx($img), imagesy($img));
			imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
			imagealphablending($bg, TRUE);
			imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

			imagejpeg($bg, NULL, 30); //passes to stream
			imagedestroy($img);
			imagedestroy($bg);
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
		if ($extension == "wav")
			header("Content-Type: audio/vnd.wave");
		else
			header("Content-Type: audio/mpeg");
	}
	if ($result[0]["Type"] == File::$types["MOVIE"]) //AMBIGUOUS NEEDS CLARIFICATION
	{
		if (isset($_GET["t"]))
			$thumbnail = true;
		
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
		if ($extension == "txt")
		{
			header("Content-Type: text/plain");
		}
		else
		{
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
			header("Content-Type: application/octet-stream");
			header('Content-Disposition: attachment; filename="' . $result[0]["FilePath"] . '"');
		}
	}
	logFileView($result[0]["File_ID"], NULL, NULL, FILE_PHP); //for everything BUT thumbnails
	$fp = fopen($completeFilePath, 'rb');
	header("Content-Length: ".filesize($completeFilePath));
	header('filename="' . $result[0]["FilePath"] . '"');
	fpassthru($fp);

	exit();
?>
