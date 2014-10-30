<?php
require_once("dbcon.php");
define("DEFAULT_FILE_STORAGE_PATH", "/home/vadwebData/");
define("MULTI_FILE_UPLOAD_NUM_LIMIT", 20);
define("FILE_SIZE_LIMIT", 4999999999);
define("LISTING_MODE", 1);
define("VIEWING_MODE", 2);

define("GROUP_NONE", 0);
define("GROUP_REGISTERED", 1);
define("GROUP_FRIENDS", 2);
define("GROUP_ADMIN", 4);

define("FILE_PHP", 1);
define("VIEW_PHP", 2);
//TODO Figure out what is wrong with logins and why it logs out (cookie expires at random times).
//TODO add file renaming feature built in, or in case error in file name
//TODO Figure out what happens if the file has special characters in name
//TODO Figure out what happens if the file requested to be viewed is not found
//TODO Add better view tracking, with separate view from javascript and for the file from php
//TODO Work on about page
//TODO Allow modification of uploaded files
//TODO Add file management for admins
//TODO improve data collection
//TODO to views, add html origin of link
//TODO add uplisted viewing, figure that out in permissions and make sure the user can see own files?
//TODO add the different highlights for your files etc
//TODO add user search and user sharing
//TODO add user settings
//TODO add file search, sorting
//TODO load files in pages
//TODO improve view counting tracking, add view count to file view page (also other details about file, user)
//TODO Ajax file uploading and turn error codes into useable things
//TODO Add filesize tracking in mysql? or at least print it in files.php
//TODO name randomization
//TODO display file permissions in files.php

class File
{
    public $name, $nameNoEXT, $extension, $type, $size, $absPath;

    public static $types = [
    "PICTURE" => "picture",
    "FLASH" => "flash",
    "AUDIO" => "audio",
    "MOVIE" => "movie",
    "PDF" => "pdf",
    "OTHER" => "other"
    ];

    public static $pictureEXTs = array("tiff", "tif", "jpeg", "jpg", "gif", "png");
    public static $flashEXTs = array("swf");
    public static $audioEXTs = array("mp3", "wav");
    public static $movieEXTs = array("mov", "mp4", "avi");
    public static $pdfEXTs = array("pdf");
    
    public function setAbsPath($absPath)
    {
        $this->$absPath = $absPath;
    }
    public function __construct($nameWithEXT)
    {
        $this->name = $nameWithEXT;
        $nameArray = explode(".", $nameWithEXT);
        $this->nameNoEXT = current($nameArray);
        $this->extension = strtolower(end($nameArray));
        if (in_array($this->extension, File::$pictureEXTs))
            $this->type = "picture";
        else if (in_array($this->extension, File::$flashEXTs))
            $this->type = "flash";
        else if (in_array($this->extension, File::$audioEXTs))
            $this->type = "audio";
        else if (in_array($this->extension, File::$movieEXTs))
            $this->type = "movie";
        else if (in_array($this->extension, File::$pdfEXTs))
            $this->type = "pdf";
        else
            $this->type = "other";
    }
    public function setType($type)
    {
        $this->$type = $type;
    }
    public function setSize($size)
    {
        $this->$size = $size;
    }
}

class UploadedFile extends File
{
    public $tmpUploadName, $uploadError, $permissions;
    
    public function __construct($nameWithEXT, $tmpUploadName, $size, $uploadError, $perms)
    {
        parent::__construct($nameWithEXT);
        $this->tmpUploadName = $tmpUploadName;
        $this->uploadError = $uploadError;
        $this->size = $size;
        $this->permissions = $perms;
    }
    public function isError()
    {
        if ($this->uploadError != 0)
            return true;
        return false;
    }
    public function validateFileForErrors()
    {
        if (strlen($this->name) > 100)
            $this->uploadError = 20;
        if ($this->size > FILE_SIZE_LIMIT)
            $this->uploadError = 21;
        if (sizeof(explode(".", $this->name)) > 2)
            $this->uploadError = 22;
        if ($this->size == 0)
            $this->uploadError = 23;
        if (sizeof(explode("'", $this->name)) > 1)
            $this->uploadError = 24;
    }
    public function evaluatePerms()
    {
        $group = substr($this->permissions, 0, 1);
        $userPlusPart = substr($this->permissions, strpos($this->permissions, '|')+1);
        $userMinusPart = "";
        $this->permissions = "+G(".$group.")".$userPlusPart.$userMinusPart;
    }
    public function prepFile()
    {
            //TODO 1this function compresses or prepares photos, calculates permissions, etc all for writing to mysql
        $this->evaluatePerms();
        if ($this->type == File::$types["PICTURE"])
        {
                //do picture stuff
                //if file is image, take image data as text and store it in memory from tmp file, then right to new file in here (then call mysql); return true so store does nothing more
        }

        return 0;
    }
    public function writeToMySQL()
    {
        mysqlFileWrite($this->absPath, $this->nameNoEXT, $this->extension, $this->type, $this->permissions, getCurrentUsername());
        return true;
    }
    public function storeFile()
    {
        if ($this->isError())
            return -1;
        $prepResult = $this->prepFile();
        if ($prepResult == -1)
            return -2;
        else if ($prepResult == 1)
            return true;

        $this->name = $this->nameNoEXT . "." . $this->extension;
        $fullPathForSaving = DEFAULT_FILE_STORAGE_PATH . $this->name;
        $counter = 1;
        while (file_exists($fullPathForSaving))
        {
            $fullPathForSaving = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . "_" . $counter . "." . $this->extension;
            if (!file_exists($fullPathForSaving))
            {
                $this->nameNoEXT = $this->nameNoEXT . "_" . $counter;
                $this->name = $this->nameNoEXT . "." . $this->extension;
                break;
            }
            $counter ++;
        }
        if (!$this->writeToMySQL())
            return -3;
        if (move_uploaded_file($this->tmpUploadName, $fullPathForSaving))
            return true;

        return -4;
    }
}

function printNavBarForms($fileToRedir = NULL)
{
    if (isset($fileToRedir) and !empty($fileToRedir))
        $fileToRedir = "?redirLoc=".$fileToRedir;
    echo
    '<form class="navbar-form navbar-right" role="form" action="/login.php'. $fileToRedir .'" method="post"'; if (isLoggedIn()) echo 'hidden="hidden"';
    echo'
    >
    <div class="form-group">
      <input type="text" placeholder="Username" id="username" name="username" class="form-control">
  </div>
  <div class="form-group">
      <input type="password" placeholder="Password" id="password" name="password" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Sign in</button>
</form>
';
echo'
<form class="navbar-form navbar-right" role="form" action="/logout.php" method="get"'; if (!isLoggedIn()) echo 'hidden="hidden"';

echo '
>
<button type="submit" class="btn btn-danger">Logout</button>
</form>'
;
}
function readFileList($sort="CreatedTime", $order="") {
    if (!isLoggedIn())
        return;
    $query = "SELECT * FROM Files ORDER BY ".$sort." ".$order.";";
    $sql = SQLCon::getSQL();
    $result = $sql->sQuery($query)->fetchAll();
    return $result;
}
function mysqlFileWrite($absPath = NULL, $nameNoEXT = NULL, $ext = NULL, $type = NULL, $perms = NULL, $username = NULL)
{
    $user_id = getID($username);
    $sql = SQLCon::getSQL();
    $stmt = $sql->sQuery("INSERT INTO Files (User_ID, FilePath, Permission, Type) VALUES ('$user_id', '$nameNoEXT.$ext', '$perms', '$type')");
}
function canViewFileByName($filename = NULL, $action = VIEWING_MODE)
{
    /*if (!isLoggedIn())
        return false;
    else
        return true;*/

    $sql = SQLCon::getSQL();
    if ($filename == NULL || empty($filename))
        return false;
    
    $result = $sql->sQuery("SELECT User_ID, Permission from Files where FilePath='$filename'")->fetchAll();

    $perm = $result[0]["Permission"];
    $uploadUID = $result[0]["User_ID"];
    $currentUserID = getID(getCurrentUsername());
    
    //if (getCurrentUserID() == $uploadUID) return true; //allows one to see his own posts, cant blcks oneself from seeing own posts
    $minGroup = substr($perm, strpos($perm, "+G(") + 3, 1);
    $unlisted = strpos($perm, "-&");

    if ($action == LISTING_MODE)
    {
        if ($unlisted == true)
            return false;
    }
    if (currentLogin() >= $minGroup)
        return true;

    return false;

    /*

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
        //TODO Redo all of this using new group mechanics
        
        if (!isAdmin())
            $tilPos = false;
        if (!validLogin())
            $starPos = false;
        
        /*if ($currentTask == "list") //if we are listing
        {
            if (isUnlisted($fileID, $perm))
            {
                if ($currentUserID != $uploadUID)
                    return false;
            }
        }*/
        /*
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
        */
    }
    function highestUploadID()
    {
        $sql = SQLCon::getSQL();
        $result = $sql->sQuery("SELECT File_ID FROM Files ORDER BY File_ID DESC")->fetch();
        return $result["File_ID"];
    }    
    function userExists($username, $email)
    {
        $sql = SQLCon::getSQL();
        
        $stmt = $sql->sQuery("SELECT * FROM UserData WHERE (Username='$username' OR Email='$email')");
        $res = $stmt->fetchAll();
        $num = count($res);
        if ($num !== 0)
            return true;
        $stmt->closeCursor();
        
        return false;
    }
    function invalidUsername($username)
    {
        if ($username == "" or is_numeric($username))
            return true;
        return false;
    }
    function invalidEmail($email)
    {
        if ($email == "" or is_numeric($email))
            return true;
        $atpos = strrpos($email, '@');
        $dotpos = strrpos($email, '.');
        if ($atpos<1 or $dotpos<$atpos+2 or $dotpos+2>=strlen($email))
            return true;
        return false;
    }
    function invalidYear($yob)
    {
        if ($yob == "" or !is_numeric($yob))
            return true;
        if ($yob < 1900 or $yob >= (date("Y") - 6))
            return true;
        return false;
    }
    
    function custHash($text = null)
    {
        return hash("sha512", $text);
    }
    
    function writeUser($username, $email, $yob, $pass)
    {
        $regIp = $_SERVER['REMOTE_ADDR'];
        $proxIp = NULL;
        $proxIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $sql = SQLCon::getSQL();
        $passSHA = custHash($pass);
        $qSt = "INSERT INTO UserData (Username, Email, YOB, Password, IP, IPwithProxy) VALUES ('$username', '$email', '$yob', '$passSHA', '$regIp', '$proxIp')";
        $query = $sql->sQuery($qSt);
        if (!$query)
            return false;
        else
            return true;
    }
    
    function incrementPerfCount($name = NULL)
    {
        if ($name == NULL)
            return;
        $sql = SQLCon::getSQL();    
        $count = count($sql->sQuery("select * from PerformanceDebug where Type='$name'")->fetchAll());
        if ($count == 0)
            $sql->sQuery("insert into PerformanceDebug (Type, Value) values ('$name', 0)");
        
        $sql->sQuery("UPDATE PerformanceDebug SET Value=Value+1 WHERE  Type='$name'");
    }

    function canUpload()
    {
        if (currentLogin() >= GROUP_REGISTERED)
            return true;
        return false;
    }
    
    function currentLogin()
    {
        if (isset($_SESSION['cachedUserGroup']) and !empty($_SESSION['cachedUserGroup']))
            return $_SESSION['cachedUserGroup'];
        $sql = SQLCon::getSQL();
        $user_check = getCurrentUsername();
        $result = $sql->sQuery("select Username, GroupVal from UserData where Username='$user_check'")->fetchAll(); //todo fix< bad
        //incrementPerfCount("CurrentLogin");
        if (!isset($result) or empty($result))
            return GROUP_NONE;
        
        $login_session_username = $result[0]["Username"]; //this is the username that we looked up.
        
        if (empty($login_session_username) or !isset($login_session_username))
            return GROUP_NONE;
        else
        {
            $_SESSION['cachedUserGroup'] = $result[0]["GroupVal"];
            return $result[0]["GroupVal"];
        }
        
        return GROUP_NONE;
    }

    function isLoggedIn()
    {
        if (currentLogin() == GROUP_NONE)
            return false;
        else
            return true;
    }
    
    function isAdmin()
    {
        if (currentLogin() >= GROUP_ADMIN)
            return true;
        return false;
    }
    
    function getCurrentUsername()
    {
        if (isset($_SESSION['loggedInUsername']) and !empty($_SESSION['loggedInUsername']))
            return $_SESSION['loggedInUsername'];
    }
    
    function getID($username)
    {
        $sql = SQLCon::getSQL();
        $stmt="SELECT ID FROM UserData WHERE Username='$username'";
        $result=$sql->sQuery($stmt)->fetchAll();
        if (!$result)
            return null;
        return $result[0][0];
    }

    function getUsername($id)
    {
        $sql = SQLCon::getSQL();
        $stmt="SELECT Username FROM UserData WHERE ID='$id'";
        $result=$sql->sQuery($stmt)->fetchAll();
        return $result[0][0];
    }

    function logGenericPageView($pageName=null)
    {
        $sql = SQLCon::getSQL();
        $regIp = $_SERVER['REMOTE_ADDR'];
        $proxIp = "";
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $proxIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $sql->sQuery("INSERT INTO GeneralViews(Page, IP, IPwithProxy, IsLoggedIn) VALUES ('$pageName', '$regIp', '$proxIp', 'isLoggedIn()')");
    }

    function logFileView($fileID = NULL, $devicetype = NULL, $duration = NULL, $source = NULL)
    {
        if ($fileID == NULL)
            return false;
        $sql = SQLCon::getSQL();

        if (isset($_SERVER['REMOTE_ADDR']))
            $regIp = $_SERVER['REMOTE_ADDR'];
        else
            $regIp = null;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $proxIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $proxIp = null;

        $id = getID(getCurrentUsername());
        if (empty($id))
            $stmt="INSERT INTO FileViews(File_ID, User_ID, IP, IPwithProxy, Device, ViewSource, Duration) VALUES ('$fileID', NULL, '$regIp','$proxIp','$devicetype', '$source', '$duration');";
        else
            $stmt="INSERT INTO FileViews(File_ID, User_ID, IP, IPwithProxy, Device, ViewSource, Duration) VALUES ('$fileID', '$id', '$regIp','$proxIp','$devicetype', '$source', '$duration');";

        if ($sql->sQuery($stmt) != NULL)
            return true;
        
        return false;
    }
    
    function logLogin($username, $attemptedPassword, $success)
    {
        $sql = SQLCon::getSQL();
        $regIp = $_SERVER['REMOTE_ADDR'];
        $proxIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        
        $person = getID($username); //TODO Fix because this glitches out when the usename doesnt correspond to anyone
        $id = $person[0];
        
        $stmt="INSERT INTO LoginAttempts(Username, Password, IP, IPwithProxy, Success) VALUES ('$username', '$attemptedPassword', '$regIp','$proxIp','$success')";
        if ($sql->sQuery($stmt) != NULL)
            return true;
        
        return false;
    }
    
    function login($username = NULL, $password = NULL, $alreadyHashed = true)
    {
        $sql = SQLCon::getSQL();
        ini_set('session.use_only_cookies',1);
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], false, true);
        session_regenerate_id();
        $unhashedPassword = $password;
        if (!$alreadyHashed)
            $password = custHash($password);
        
        $result = $sql->sQuery("SELECT ID FROM UserData WHERE Username='$username' AND Password='$password'")->fetchAll();
        if (count($result) == 1)
        {
            //TODO fix logLogin so it saves username not id
            logLogin($username, $password, true);
            $_SESSION['loggedInUsername']=$username;
            return true;
        }
        else
        {
            logLogin($username, $unhashedPassword, false);
        }
        return false;
    }
    ?>
