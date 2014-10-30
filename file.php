<?php
	ob_clean();
	require_once "util.php";
	$fileName = $_GET["name"];
	if (!canViewFileByName($fileName, VIEWING_MODE)) {
		//echo "<h1 style='color: red; font-family: Comic Sans MS;'> major h4x0r </h1>";
		exit;
	}
	$sql = SQLCon::getSQL();
    $sql->sQuery("UPDATE Files SET ViewCount = ViewCount + 1 WHERE FilePath='$fileName'");
	$result = $sql->sQuery("SELECT * FROM Files WHERE FilePath = '$fileName'")->fetchAll();
	logFileView($result[0]["File_ID"], NULL, NULL, FILE_PHP);

	$completeFilePath = DEFAULT_FILE_STORAGE_PATH . $result[0]["FilePath"];
	$fp = fopen($completeFilePath, 'rb');

	if ($result[0]["Type"] == File::$types["PICTURE"])
	{

		header("Content-Type: image/jpeg");
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
		header("Content-Type: video/mp4");
	}
	if ($result[0]["Type"] == File::$types["PDF"])
	{
		header("Content-Type: application/pdf");
	}
	if ($result[0]["Type"] == File::$types["OTHER"]) //POTENTIALLY AMBIGUOUS NEEDS CLARIFICATION
	{
		header("Content-Type: application/octet-stream");
	}
	header("Content-Length: ".filesize($completeFilePath));
	fpassthru($fp);
	exit();
?>
