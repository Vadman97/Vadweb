<?php
require_once('dbconfig.php');
$GLOBALS['uploadsPath'] = "uploads/";
$GLOBALS['pics'] = array("jpeg", "jpg", "png");
$GLOBALS['mp4'] = array("mp4");
$GLOBALS['tiffs'] = array("tiff", "tif");

//ini_set('display_errors', 'On');
error_reporting(E_ALL);

function validLogin()
{
	//echo "Starting login check" . "<br>";
	$user_check=DB::$con->real_escape_string(getCurrentUserName());
	//echo "Session stuff" . "<br>";
	$ses_sql=DB::$con->query("select Username from LoginData where Username='$user_check'");
	
	$row=mysqli_fetch_array($ses_sql);
	
	$login_session=$row['Username'];
	
	//echo "SQL Proccessed" . "...<br>" . $_SESSION['login_user_vadweb'] . "  []  " . $row['Username'] . "<br>";
	
	if(!isset($login_session))
	{
		//echo "Log in failure.<br>";
		//header("Location: /");
		return false;
	}
	else
	{
		//echo "Log in success.<br>";
		//header("Location: /");
		if (redirTerms())
		return true;
	}
	return false;
}
function isAdmin()
{
	if (validLogin())
	{
		$sql="SELECT * FROM AdminData";
		$resultN=DB::$con->query($sql);
		$countN=mysqli_num_rows($resultN);
		for ($x=0; $x<$countN; $x++)
		{
			$row=mysqli_fetch_array($resultN);
			if (getCurrentUserName() == $row[0])
			{
				return true;
			}
		}
	}
	return false;
}
function isSU()
{
	if (getCurrentUserName() == 'Vadman' && getCurrentUserID() == '2')
	return true;
	if (getCurrentUserName() == 'swag' && getCurrentUserID() == '6')
	return true;
	if (getCurrentUserName() == 'andars' && getCurrentUserID() == '3')
        return true;
	
	return false;
}
function canViewUploads()
{
	return true;
	if (validLogin())
	//if (isAdmin())
	return true;
	return false;
}
function canSingleUpload()
{
	if (validLogin())
	return true;
	return false;
}
function canGeneralUpload()
{
	if (validLogin())
	return true;
	return false;
}
function canMultiUpload()
{
	if (isAdmin())
	return true;
	return false;
}
function isUnlisted($fileID, $_perm = NULL)
{
	if ($_perm == NULL)
	{
		$s_fileID = DB::$con->real_escape_string($fileID);
		$sql="select * from FileUploads where upload_id='$s_fileID'";
		$result = DB::$con->query($sql);
		$row=mysqli_fetch_array($result);
		$perm = $row[3];
	}
	else
	{
		$perm = $_perm;
	}
	$unlistPost = strpos($perm, "-&");
	
	if ($unlistPost !== false) //if post has a "-&" in it
	return true;
	
	return false;
}
function canViewFileByID($fileID = NULL, $currentTask) //$currentTask is either view or list
{
	if ($fileID == NULL || empty($fileID))
	return false;
	
	$sql="select user_id, FilePerms from FileUploads where upload_id='$fileID'";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	$perm = $row[1];
	$uploadUID = $row[0];
	$currentUserID = getCurrentUserID();
	
	//if (getCurrentUserID() == $uploadUID) return true; //allows one to see his own posts, cant blcks oneself from seeing own posts
	
	$negSelf = "-".$currentUserID;
	$posSelf = "+".$currentUserID;
	$negSelfPos = strpos($perm, $negSelf);
	$posSelfPos = strpos($perm, $posSelf);
	if ($currentUserID == NULL || !is_numeric($currentUserID))
	{
		$negSelfPos = false; $posSelfPos = false;
	}
	
	$starPos = strpos($perm, "*"); //star is registered user perm
	$tilPos = strpos($perm, "~"); //tilda is admin perm
	$carrotPos = strpos($perm, "^"); //carrot is full public perm
	
	if (!isAdmin())
	$tilPos = false;
	if (!validLogin())
	$starPos = false;
	
	if ($currentTask == "list") //if we are listing
	{
		if (isUnlisted($fileID, $perm))
		{
			if ($currentUserID != $uploadUID)
				return false;
		}
	}
	
	if ($starPos !== false) //if * is in the perm
	{
		if(!$negSelfPos) //if you are not prohibited explicitely
			return true;
		else
			return false;
	}
	else if ($tilPos !== false) //if ~ is in the perm
	{
		if(!$negSelfPos) //if you are not prohibited explicitely
			return true;
		else
			return false;
	}
	else if ($carrotPos !== false) //if ^ is in the perm
	{
		if(!$negSelfPos) //if you are not prohibited explicitely
			return true;
		else
			return false;
	}
	else
	{
		if($posSelfPos !== false && !$negSelfPos) //if you are added but also not prohibited (you can block yourself i think...)
			return true;
		else
			return false;
	}
	return false;
}
function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = DB::$con->real_escape_string($data);
	return $data;
}
function setTermsApproval($val)
{
	if ($val !== true && $val !== false)
	$val = false;
	
	$id = DB::$con->real_escape_string(getCurrentUserID());
	
	$sql="update LoginData set TermsApproval='$val' where ID='$id'";
	if (DB::$con->query($sql))
		return true;
}

function getTermsApproved()
{
	//return true; //////!!!!!!!!!!!!!!!!!!!!!!!!IMPORTANT REMOVE
	$id = getCurrentUserID();
	$sql="select * from LoginData where ID='$id'";
	
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	
	if ($row[7] == 1)
		return true;
	else
		return false;
}
function redirTerms()
{
	if (getTermsApproved())
		return true;
	else
	{
		header("Location: /terms.php");
		return false;
	}
}
function getSimpleViews($fileID)
{
	$sql="select views from FileUploads where upload_id='$fileID'";
	$res = DB::$con->query($sql);
	$row=mysqli_fetch_array($res);
	return $row[0];
}
function getComplexViews($fileID)
{
	$sql="select * from FileViews where file_id='$fileID' AND view_duration >= 1000"; //maybe sort views to throw out quick refresh views from same user?
	$res = DB::$con->query($sql);
	$count=mysqli_num_rows($res);
	return $count;
}
function writeSimpleView($fileID)
{
	$views = getSimpleViews($fileID);
	
	if ($views === NULL)
	{
		$sql="update FileUploads set views='0' where upload_id='$fileID'";
		DB::$con->query($sql);
	}
	$valSet = (int) $views + 1;
	$sql="update FileUploads set views='$valSet' where upload_id='$fileID'";
	DB::$con->query($sql);
}
function writeComplexView($fileID, $deviceType = NULL, $viewDur = 0)
{
	$regIp = $_SERVER['REMOTE_ADDR'];
	$proxIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$userID = getCurrentUserID();
	if ($userID == NULL || !is_numeric($userID))
	{
		$userID = NULL;
		echo "User ID Null";
		$sql="INSERT INTO FileViews (file_id, IP, IPwithProxy, device, view_duration) VALUES ('$fileID', '$regIp', '$proxIp', '$deviceType', '$viewDur')";
		return DB::$con->query($sql);
	}
	$sql="INSERT INTO FileViews (file_id, user_id, IP, IPwithProxy, device, view_duration) VALUES ('$fileID', '$userID', '$regIp', '$proxIp', '$deviceType', '$viewDur')";
	DB::$con->query($sql);
}
function writeUser($un, $em, $ag, $pass)
{
	require_once 'dbconfig.php';
	$safe_un = DB::$con->real_escape_string($un);
	$safe_em = DB::$con->real_escape_string($em);
	$safe_ag = DB::$con->real_escape_string($ag);
	$safe_pass = DB::$con->real_escape_string($pass);
	
	$hashed_pass = sha1($safe_pass);
	$regIp = $_SERVER['REMOTE_ADDR'];
	$proxIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
	
	$sql="INSERT INTO LoginData (Username, Email, Age, Password, IP, IPwithProxy) VALUES ('$safe_un','$safe_em','$safe_ag','$hashed_pass', '$regIp', '$proxIp')";

	if (!DB::$con->query($sql))
	{
		die('Error: ' . mysqli_error());
	}
	else
	{
		echo "Thanks for registering, you will be redirected to the home page now.";
	}
}
function editUser($un, $em, $ag, $pass, $id)
{
	require_once 'dbconfig.php';
	$safe_un = DB::$con->real_escape_string($un);
	$safe_em = DB::$con->real_escape_string($em);
	$safe_ag = DB::$con->real_escape_string($ag);
	$safe_pass = DB::$con->real_escape_string(sha1($pass));
	
	if ($em == NULL)
	$sql="update LoginData set Username='$safe_un', Age='$safe_ag', Password='$safe_pass' where ID='$id'";
	else
	$sql="update LoginData set Username='$safe_un', Email='$safe_em', Age='$safe_ag', Password='$safe_pass' where ID='$id'";

	if (!DB::$con->query($sql))
	{
	  die('Error: ' . mysqli_error());
	}
	else
	{
		//echo "Thanks for registering, you will be redirected to the home page now.";
	}
}
function writeFileUploadData($tup = NULL, $perms = "*", $isSneakyMovie = false) //tup is tempUploadPath
{
	if ($tup == NULL) return false;
	if ($perms == NULL) return false;
	
	$alphas = array_merge(range('A', 'Z'), range('a', 'z'));
	$nums = range(1,getMaxUserID());
	
	foreach($alphas as $letter)
	{
		if (strpos($perms, $letter)!== false)
			return false;
	}
	
	$indexOfSlash = strpos($tup, "/");
	$tup = substr($tup, $indexOfSlash + 1);
	
	$uname = getCurrentUserName();
	$sql="select * from LoginData where Username='$uname'";
	
	$result=DB::$con->query($sql) or die("ERROR with selecting id");
	$row=mysqli_fetch_array($result);
	$count=mysqli_num_rows($result);
	if ($isSneakyMovie === true)
		$movVal = true;
	else
		$movVal = false;
		
	$uID = $row[0];
	
	$sql="insert into FileUploads(user_id, FilePath, FilePerms, is_movie) values ('$uID','$tup','$perms','$movVal')"; 
	$result=DB::$con->query($sql) or die("ERROR with writing upload action");
	
	if (!$result)
	return false;
	return true;
}
function getUploadSizeFromShortPath($filePath)
{
	$nameArray = explode(".", $filePath);
	$extension = strtolower(end($nameArray));
	$vid = array("mp4");
	$isFileVid = in_array($extension, $vid);
	
	if ($isFileVid)
	{
		$absolutePath = "/home/vadim/websiteVids/";
	}
	else
	{
		$absolutePath = "/home/vadim/mysite/uploads/";
	}
	$completePath = $absolutePath . $filePath;
	return $completePath;
	$fileSize = filesize($completePath);
	$fileSizeMB = $fileSize / 1024 / 1024;
	return floor($fileSizeMB * 1000) / 1000;
}
function getUploadSizeFromFileName($fileName)
{
	$nameArray = explode(".", $fileName);
	$extension = strtolower(end($nameArray));
	$vid = array("mp4");
	$isFileVid = in_array($extension, $vid);
	
	if ($isFileVid)
	{
		$absolutePath = "/home/vadim/websiteVids/";
	}
	else
	{
		$absolutePath = "/home/vadim/mysite/uploads/";
	}
	$completePath = $absolutePath . $fileName;
	$fileSize = filesize($completePath);
	$fileSizeMB = $fileSize / 1024 / 1024;
	return floor($fileSizeMB * 1000) / 1000;
}
function nameContainsExtension($fileName, $extension)
{
	$nameArray = explode(".", $fileName);
	$extensionInFile = strtolower(end($nameArray));
	$targetExtension = array($extension);
	return in_array($extensionInFile, $targetExtension);
}
function getUserID($username = NULL)
{
	if ($username == NULL) return NULL;
	
	$sql="select ID from LoginData where Username='$username'";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	return $row[0];
}
function getMaxUserID()
{	
	$sql="select ID from LoginData order by ID DESC";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	return $row[0];
}
function getMaxUploadID()
{	
	$sql="select upload_id from FileUploads order by upload_id DESC";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	return $row[0];
}
function getUsernameFromID($id = NULL)
{
	if ($id == NULL) return NULL;
	
	$sql="select Username from LoginData where ID='$id'";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	return $row[0];
}
function getUserInfoFromID($id = NULL)
{
	if ($id == NULL) return NULL;
	
	$sql="select * from LoginData where ID='$id'";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	return $row;
}
function getCurrentUserID()
{
	$tempU = getCurrentUserName();
	$sql="select ID from LoginData where Username='$tempU'";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	return $row[0];
}
function getCurrentUserName()
{
	if (isset($_SESSION['login_user_vadweb']))
		return $_SESSION['login_user_vadweb'];
		return;
}
function getAllUsers()
{
	$sql="select Username from LoginData";
	$result = DB::$con->query($sql);
	return $result;
}
function getUploadPathFromID($id = NULL, $videoSpec = false)
{
	if ($id == NULL) return NULL;
	$sql="select * from FileUploads where upload_id='$id'";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	if ($row[5] == 1 && $videoSpec === false)
	{		
		//echo $row[2];
		$lengthOfPath = strlen("websiteVids/");
		$indexOfLastSlash = strrpos($row[2], "websiteVids/");
		//echo $indexOfLastSlash;
		$proper = "uploads/" . substr($row[2], $indexOfLastSlash + $lengthOfPath);
		//echo $proper;
		//die();
		return $proper;
	}
	else
	{
		return $row[2];
	}
}
function getUploadShortPathFromID($id2 = NULL)
{
	if ($id2 == NULL) return NULL;
	$tLongPath = getUploadPathFromID($id2);
	
	$lengthOfPath = strlen($GLOBALS['uploadsPath']);
	$indexOfSlash = strrpos($tLongPath, $GLOBALS['uploadsPath']);
	$shortPath = substr($tLongPath, $indexOfSlash + $lengthOfPath);
	return $shortPath;
}
function getUploadShortPathFromLongName($longName = NULL)
{
	if ($longName == NULL) return NULL;
	
	$lengthOfPath = strlen($GLOBALS['uploadsPath']);
	$indexOfSlash = strrpos($longName, $GLOBALS['uploadsPath']);
	$shortPath = substr($longName, $indexOfSlash + $lengthOfPath);
	return $shortPath;
}
function getUsernameFromUploadID($id2 = NULL)
{
	if ($id2 == NULL) return NULL;
	
	$sql="select * from FileUploads where upload_id='$id2'";
	$result = DB::$con->query($sql);
	$row=mysqli_fetch_array($result);
	$userID = $row[1];
	return getUsernameFromID($userID);
}
function getUploadedArray($sortSetting = NULL)
{
	if ($sortSetting == "user")
		$sql = "select * from FileUploads ORDER BY user_id ASC";
	else if ($sortSetting == "file")
		$sql = "select * from FileUploads ORDER BY FilePath ASC";
	else
		$sql = "select * from FileUploads ORDER BY upload_id DESC";
		
	$result = DB::$con->query($sql);
	$count=mysqli_num_rows($result);
	
	return $result;
}
function getUploadedArrayWithIndexRow($sortSetting = NULL)
{
	if ($sortSetting == "user")
		$sql = "
		SET @rank=0;
		SELECT @rank:=@rank+1 as rowIndex, upload_id, user_id, FilePath, FilePerms, upload_time, is_movie
		  FROM FileUploads
		  ORDER BY user_id ASC;
		";
	else if ($sortSetting == "file")
		$sql = "
		SET @rank=0;
		SELECT @rank:=@rank+1 as rowIndex, upload_id, user_id, FilePath, FilePerms, upload_time, is_movie
		  FROM FileUploads
		  ORDER BY FilePath ASC;
		";
	else
		$sql = "
		SET @rank=0;
		SELECT @rank:=@rank+1 as rowIndex, upload_id, user_id, FilePath, FilePerms, upload_time, is_movie
		  FROM FileUploads
		  ORDER BY rowIndex ASC;
		";
}
function isExistingUser($tempUser, $tempEmail)
{
	$sqlCheck="SELECT * FROM LoginData WHERE (Username='$tempUser' OR Email='$tempEmail')";
	$result=DB::$con->query($sqlCheck);
	$count=mysqli_num_rows($result);
	if ($count === 0)
	return false;
	return true;
}
echo '<script>console.log("Lock file loaded...");</script>';
?>
