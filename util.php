<?php
require_once("dbcon.php");
define("DEFAULT_FILE_STORAGE_PATH", "/home/vadwebData/");
define("MULTI_FILE_UPLOAD_NUM_LIMIT", 10);
define("FILE_SIZE_LIMIT", getUserUploadSizeLimit());
define("LISTING_MODE", 1);
define("VIEWING_MODE", 2);

define("GROUP_NONE", 0);
define("GROUP_REGISTERED", 1);
define("GROUP_FRIENDS", 2);
define("GROUP_ADMIN", 4);

define("FILE_PHP", 1);
define("VIEW_PHP", 2);

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
    public $tmpUploadName, $uploadError, $minGroup, $unlisted, $otherPerms, $description;
    
    public function __construct($nameWithEXT, $tmpUploadName, $size, $uploadError, $minGroup, $otherPerms, $description)
    {
        parent::__construct($nameWithEXT);
        $this->tmpUploadName = $tmpUploadName;
        $this->uploadError = $uploadError;
        $this->size = $size;
        $this->minGroup = $minGroup;
        $this->otherPerms = $otherPerms;
        $this->description = $description;
        $this->unlisted = false;
        if (empty($this->description))
            $this->description = $this->nameWithEXT;
    }
    public function setUnlisted()
    {
        $this->unlisted = true;
    }
    public function isError()
    {
        if ($this->uploadError != 0)
            return true;
        return false;
    }
    public function validateFileForErrors()
    {
        if ($this->size > FILE_SIZE_LIMIT)
            $this->uploadError = 21;
        if ($this->size == 0)
            $this->uploadError = 22;
        if (strlen($this->name) > 100 || sizeof(explode(".", $this->name)) > 2 || sizeof(explode("'", $this->name)) > 1 || sizeof(explode("&", $this->name)) > 1 || sizeof(explode("?", $this->name)) > 1)
            $this->nameNoEXT = generateRandomLetterString(10);
        if (strlen($this->description) > 300)
            $this->uploadError = 23;
        if (strlen($this->description) < 3)
            $this->description = $this->nameNoEXT;
    }
    public function evaluatePerms()
    {
        /*$group = substr($this->permissions, 0, 1);
        $userPlusPart = substr($this->permissions, strpos($this->permissions, '|')+1);
        $userMinusPart = "";
        $this->permissions = "+G(".$group.")".$userPlusPart.$userMinusPart;*/
        /*if (strpos($this->otherPerms, "-&"))
            $this->unlisted = 1;
        else
            $this->unlisted = 0;*/
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
    public function convertVideo()
    {
        if ($this->type == File::$types["MOVIE"])
        {
            echo "STARTING VIDEO PROCESSING <br>";

            /*$suffix = "_conv_ogg";
            $inputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . "." . $this->extension;
            $outputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . $suffix . ".ogg";
            echo shell_exec("avconv -i " . $inputFileName . " -c:v libtheora -qscale:v 7 -c:a libvorbis -qscale:a 8 " . $outputFileName);*/

            $suffix = "_conv_acc";
            $inputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . "." . $this->extension;
            $outputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . $suffix . ".mp4";
            $escapedInputFileName = str_replace(" ", "\ ", $inputFileName);
            $escapedOutputFileName = str_replace(" ", "\ ", $outputFileName);

            //echo "<br>avconv -i " . $escapedInputFileName . "  -c:v libx264 -profile:v main -level:v 41 -crf 25 -crf_max 35 -c:a aac -strict experimental -preset ultrafast -movflags +faststart " . $escapedOutputFileName . "<br>";

            echo shell_exec("avconv -i " . $escapedInputFileName . "  -c:v libx264 -profile:v main -level:v 41 -crf 25 -crf_max 35 -c:a aac -strict experimental -preset ultrafast -movflags +faststart " . $escapedOutputFileName);

            /*$suffix = "_conv_ipad";
            $inputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . "." . $this->extension;
            $outputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . $suffix . ".mp4";
            echo shell_exec("avconv -i " . $escapedInputFileName . "  -c:v libx264 -profile:v baseline -level:v 32 -s 1024x768 -crf 30 -crf_max 40 -c:a libvo_aacenc -preset ultrafast -movflags +faststart " . $escapedOutputFileName);*/
            $suffix = "_conv";
            $inputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . "." . $this->extension;
            $outputFileName = DEFAULT_FILE_STORAGE_PATH . $this->nameNoEXT . $suffix . ".mp4";
            $escapedInputFileName = str_replace(" ", "\ ", $inputFileName);
            $escapedOutputFileName = str_replace(" ", "\ ", $outputFileName);
            echo shell_exec("avconv -i " . $escapedInputFileName . "  -c:v libx264 -profile:v main -crf 25 -crf_max 35 -c:a libvorbis -qscale:a 8 -preset ultrafast -movflags +faststart " . $escapedOutputFileName);

            //$this->nameNoEXT = $this->nameNoEXT . $suffix;
            $this->extension = "mp4";
        }
    }
    public function writeToMySQL()
    {
        return mysqlFileWrite($this->absPath, $this->nameNoEXT, $this->extension, $this->type, $this->minGroup, $this->unlisted, $this->otherPerms, $this->description, getCurrentUsername());
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
        if (move_uploaded_file($this->tmpUploadName, $fullPathForSaving))
        {
            $this->convertVideo();       
            if ($this->type == File::$types["MOVIE"])
            {
                if (!mysqlFileWrite($this->absPath, $this->nameNoEXT . "_conv", $this->extension, $this->type, $this->minGroup, $this->unlisted, $this->otherPerms, $this->description, getCurrentUsername())) //main file in db
                    return -3;
                if (!mysqlFileWrite($this->absPath, $this->nameNoEXT . "_conv_ipad", $this->extension, $this->type, $this->minGroup, 2, $this->otherPerms, $this->description, getCurrentUsername())) //second format for ffox or IE
                    return -3;
                if (!mysqlFileWrite($this->absPath, $this->nameNoEXT . "_conv_acc", $this->extension, $this->type, $this->minGroup, 2, $this->otherPerms, $this->description, getCurrentUsername())) //second format for ffox or IE
                    return -3;
                return true;
            }     
            if (!$this->writeToMySQL())
                return -3;
            return true;
        }

        return -4;
    }
}

function emailAnyString($str, $subj, $email)
{
    $sql = SQLCon::getSQL();
    $to      = $email . '<' . $email . '>';
    $subject = 'Vadweb - ' . $subj;
    $message = $str;

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Vadweb Noreply Registration <vadwebnoreply@gmail.com>' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

function emailString($str)
{
    $sql = SQLCon::getSQL();
    if (!isLoggedIn())
        return;
    $id = getCurrentUserID();
    $user = $sql->sQuery("SELECT * FROM UserData WHERE ID='$id'")->fetchAll();
    $to      = $user[0][1] . '<' . $user[0][2] . '>';
    $subject = 'Vadweb - Email Verification for your User Registration';
    $message = 'Hello ' . $user[0][1] . ', ' . "please click this link to verify your email: http://www.vadweb.us/emailVerify.php?c=" . $str . "&em=" . $user[0]["Email"];
    //$message = wordwrap($message, 70, "\r\n");

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Vadweb Noreply Registration <vadwebnoreply@gmail.com>' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

function getExtension($name)
{
    $nameArray = explode(".", $name);
    $extension = strtolower(end($nameArray));
    return $extension;
}

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
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function printNavBarForms($fileToRedir = NULL)
{
    if (isset($fileToRedir) and !empty($fileToRedir))
        $fileToRedir = "?redirLoc=".$fileToRedir;
    
    if (isLoggedIn())
    {
        echo '
        <form class="navbar-form" role="form" action="/logout.php" method="get">
            <div class="row navbar-right">
		<button type="submit" class="btn btn-danger" style="display:inline;">Logout</button>
     	    </div>
	</form>';
    }
    else
    {
        /*echo '
        <form class="navbar-form navbar-right navbar-input-group" role="form" action="/login.php'. $fileToRedir .'" method="post">
		<input type="text" placeholder="Username" id="username" name="username" class="form-control">
		<input type="password" placeholder="Password" id="password" name="password" class="form-control">
		 <button type="submit" class="btn btn-success" style="display:inline;">Sign in</button>
	</form>';*/
        /*
        <div class="row">
          <div class="col-xs-2">
            <input type="text" class="form-control" placeholder=".col-xs-2">
          </div>
          <div class="col-xs-3">
            <input type="text" class="form-control" placeholder=".col-xs-3">
          </div>
          <div class="col-xs-4">
            <input type="text" class="form-control" placeholder=".col-xs-4">
          </div>
        </div>
        */
        echo '
          <form class="" role="form" action="/login.php'. $fileToRedir .'" method="post">
            <div class="row navbar-form">
                <div class="col-xs-2">
                  <input type="text" placeholder="Username" id="username" name="username" class="form-control">
                </div>
                <div class="col-xs-2">
                  <input type="password" placeholder="Password" id="password" name="password" class="form-control">
                </div>
                <div class="col-xs-1">
                  <button type="submit" class="btn btn-success">Sign in</button>
                </div>
            </div>
          </form>';
    }
}
function fibonacci($value)
{
    if ($value == 1 || $value == 2)
        return 1;
    return fibonacci($value - 1) + fibonacci($value - 2);
}
function readFileList($sort="CreatedTime", $order="") {
    if (!isLoggedIn())
        return;
    $query = "SELECT * FROM Files ORDER BY ".$sort." ".$order.";";
    $sql = SQLCon::getSQL();
    $result = $sql->sQuery($query)->fetchAll();
    return $result;
}
function mysqlFileWrite($absPath = NULL, $nameNoEXT = NULL, $ext = NULL, $type = NULL, $minGroup = NULL, $unlisted = NULL, $otherPerms = NULL, $description = NULL, $username = NULL)
{
    $user_id = getID($username);
    $sql = SQLCon::getSQL();
    if ($otherPerms == NULL)
        $otherPerms = "";
    //return $sql->sQuery("INSERT INTO Files (User_ID, FilePath, MinGroup, Unlisted, OtherPerms, Type, Description) VALUES ('$user_id', '$nameNoEXT.$ext', '$minGroup', '$unlisted', '$otherPerms', '$type', '$description')");
    $stmt = $sql->prepStmt("INSERT INTO Files (User_ID, FilePath, MinGroup, Unlisted, OtherPerms, Type, Description) VALUES (:user_id, :filePath, :minGroup, :unlisted, :otherPerms, :type, :description)");
    $sql->bindParam($stmt, ":user_id", $user_id);
    $sql->bindParam($stmt, ":filePath", $nameNoEXT.".".$ext);
    $sql->bindParam($stmt, ":minGroup", $minGroup);
    $sql->bindParam($stmt, ":unlisted", $unlisted);
    $sql->bindParam($stmt, ":otherPerms", $otherPerms);
    $sql->bindParam($stmt, ":type", $type);
    $sql->bindParam($stmt, ":description", $description);
    return $sql->execute($stmt);
}
function termsAgreed()
{
    $sql = SQLCon::getSQL();
    if (!isLoggedIn())
        return -1;
    $id = getCurrentUserID();
    $result = $sql->sQuery("SELECT TermsAgreed FROM UserData WHERE ID='$id'")->fetchAll();
    if ($result[0][0] == 1)
        return true;
    return false;
}
function emailVerified()
{
    $sql = SQLCon::getSQL();
    if (!isLoggedIn())
        return -1;
    $id = getCurrentUserID();
    $result = $sql->sQuery("SELECT Verified FROM UserData WHERE ID='$id'")->fetchAll();
    if ($result[0][0] == 1)
        return true;
    return false;
}
function verifyEmail($em)
{
    $sql = SQLCon::getSQL();
    return $sql->sQuery("UPDATE UserData SET Verified=1 WHERE Email='$em'");
}
function getUserInfo()
{
    $sql = SQLCon::getSQL();
    $id = getCurrentUserID();
    return $sql->sQuery("SELECT * FROM UserData WHERE ID='$id'")->fetchAll()[0];
}
function getFileID($filename = NULL)
{
    $sql = SQLCon::getSQL();
    return $sql->sQuery("SELECT File_ID FROM Files WHERE FilePath='$filename'")->fetchAll()[0][0];
}
function verifyCaptcha($gresponse)
{
    $secret = "6LeTUf4SAAAAAD3yTZuzqJagfsZTEf7ml5FUKAx-";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $gresponse . '&remoteip=' . $_SERVER["REMOTE_ADDR"]);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    $results = json_decode($resp, true);
    //print_r($results);
    //echo $resp;
    if ($results["success"])
        return true;
    return false;
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
    
    $result = $sql->sQuery("SELECT User_ID, MinGroup, Unlisted, OtherPerms from Files where FilePath='$filename'")->fetchAll()[0];

    /*$perm = $result[0]["Permission"];
    $uploadUID = $result[0]["User_ID"];
    $currentUserID = getID(getCurrentUsername());
    
    //if (getCurrentUserID() == $uploadUID) return true; //allows one to see his own posts, cant blcks oneself from seeing own posts
    $minGroup = substr($perm, strpos($perm, "+G(") + 3, 1);
    $unlisted = strpos($perm, "-&");*/

    /*if ($action == LISTING_MODE)
    {
        if ($unlisted == true)
            return false;
    }
    if (currentLogin() >= $minGroup)
        return true;

    return false;*/

    if ($action == LISTING_MODE)
    {
        if ($result["Unlisted"] == true)
            return false;
    }
    if (currentLogin() >= $result["MinGroup"])
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
    function getUserUploadLimit($type = "day")
    {
        if ($type == "day")
        {
            switch (currentLogin()) {
                case 2:
                    return 20;
                    break;
                case 3:
                    return 30;
                    break;
                case 4:
                    return 100;
                    break;
                case 5:
                    return 999999;
                    break;
                
                default:
                    return 10;
                    break;
            }
        }
        else //per hour
        {
            switch (currentLogin()) {
                case 2:
                    return 5;
                    break;
                case 3:
                    return 20;
                    break;
                case 4:
                    return 50;
                    break;
                case 5:
                    return 999999;
                    break;
                
                default:
                    return 3;
                    break;
            }
        }
    }
    function getUserUploadSizeLimit()
    {
        if (currentLogin() >= 2)
            return 5000000000;
        else
            return 100000000;
    }
    function uploadingCooldown()
    {
        //maybe cache when the user will next be able to upload more files in a separate var, check with that timestamp and only proceed if its blank (that will allow the following to set any large cooldown)
        $sql = SQLCon::getSQL();
        $id = getCurrentUserID();
        $results1h = $sql->sQuery("SELECT File_ID, CreatedTime FROM Files WHERE User_ID='$id' AND CreatedTime >= (DATE_SUB(now(), INTERVAL 1 HOUR))")->fetchAll();
        $results1d = $sql->sQuery("SELECT File_ID, CreatedTime FROM Files WHERE User_ID='$id' AND CreatedTime >= (DATE_SUB(now(), INTERVAL 1 DAY))")->fetchAll();
        $numRes1h = count($results1h);
        $numRes1d = count($results1d);
        if ($numRes1h >= getUserUploadLimit("hour"))
        {
            $time = $results1h[getUserUploadLimit("hour") - 1][1];
            return $sql->sQuery("SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, now(), TIMESTAMPADD(HOUR, 1, '$time')))")->fetchAll()[0][0];
        }
        else if ($numRes1d >= getUserUploadLimit("day"))
        {
            $time = $results1h[getUserUploadLimit("day") - 1][1];
            return $sql->sQuery("SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, now(), TIMESTAMPADD(HOUR, 1, '$time')))")->fetchAll()[0][0];
        }
        return false; //not on cooldown
    }
    function highestUploadID()
    {
        $sql = SQLCon::getSQL();
        $result = $sql->sQuery("SELECT File_ID FROM Files ORDER BY File_ID DESC")->fetch();
        return $result["File_ID"];
    }    
    function thumbnailCached($fileID)
    {
        $sql = SQLCon::getSQL();
        $result = $sql->sQuery("SELECT ThumbnailCached FROM Files WHERE File_ID='$fileID'")->fetch();
        if ($result[0] == 0)
            return false;
        else if ($result[0] == 1)
            return true;
    }
    function setThumbnailCached($fileID)
    {
        $sql = SQLCon::getSQL();
        $sql->sQuery("UPDATE Files SET ThumbnailCached=1 WHERE File_ID='$fileID'");
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

    function initAccountSettings($all = true)
    {
        $start = microtime();
        $sql = SQLCon::getSQL();
        if ($all)
        {
            $users = $sql->sQuery("SELECT ID FROM UserData")->fetchAll();
            $num = count($users);
            for ($i = 0; $i < $num; $i++)
            {
                $val = $users[$i][0];
                $settingHas = $sql->sQuery("SELECT ID FROM UserSettings WHERE ID='$val'")->fetchAll();
                if (!$settingHas)
                {
                    $sql->sQuery("INSERT INTO UserSettings (ID) VALUES ('$val')");
                }
            }
        }
        else
        {
            $val = getCurrentUserID();
            $settingHas = $sql->sQuery("SELECT ID FROM UserSettings WHERE ID='$val'")->fetchAll();
            if (!$settingHas)
            {
                $sql->sQuery("INSERT INTO UserSettings (ID) VALUES ('$val')");
            } 
        }
        $total = microtime() - $start;
        //echo $total;
    }
    function submitComment($comment, $filename, $superID = NULL)
    {
        //echo $comment . " " . $filename . " " . $superID;
        //die();
    	if (empty($comment) || empty($filename))
    	    return false;
    	$sql = SQLCon::getSQL();
    	$userID = getCurrentUserID();
    	$fileID = getFileID($filename);
    	if ($superID == NULL)
    	{
			$stmt = $sql->prepStmt("INSERT INTO Comments (File_ID, User_ID, Comment, SubCommentOf, Rating) VALUES (:fileID, :userID, :comment, NULL, 1)");
			$sql->bindParam($stmt, ":fileID", $fileID);
			$sql->bindParam($stmt, ":userID", $userID);
			$sql->bindParam($stmt, ":comment", $comment);
    	    if ($sql->execute($stmt) != false)
				return true;
    	}
        else
        {
			$stmt = $sql->prepStmt("INSERT INTO Comments (File_ID, User_ID, Comment, SubCommentOf, Rating) VALUES (:fileID, :userID, :comment, :superID, 1)");
			$sql->bindParam($stmt, ":fileID", $fileID);
			$sql->bindParam($stmt, ":userID", $userID);
			$sql->bindParam($stmt, ":comment", $comment);
			$sql->bindParam($stmt, ":superID", $superID);
    	    if ($sql->execute($stmt) != false)
				return true;
        }
    }

    function getCommentLimit()
    {
        return 3; //per 10 minutes per file
    }

    function commentTimeout($filename = NULL)
    {
        $sql = SQLCon::getSQL();
        $id = getCurrentUserID();
        $fileID = getFileID($filename);
        $results = $sql->sQuery("SELECT Timestamp FROM Comments WHERE User_ID='$id' AND File_ID='$fileID' AND Timestamp >= (DATE_SUB(now(), INTERVAL 10 MINUTE))")->fetchAll();
        $numRes = count($results);
        if ($numRes >= getCommentLimit())
        {
            $time = $results[getCommentLimit() - 1][0];
            return $sql->sQuery("SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, now(), TIMESTAMPADD(MINUTE, 10, '$time')))")->fetchAll()[0][0];
        }
        return false; //not on cooldown
    }

    function safeComment($comment)
    {
        //detect urls, auto make them links, html processing whatnot
        $safeComment = htmlspecialchars($comment);
        //$firstDotLocation = strpos($safeComment, ".");
        $safeComment = makeLink($safeComment);
        return $safeComment;
    }

    function callback($match)
    {
        // Prepend http:// if no protocol specified
        $completeUrl = $match[1] ? $match[0] : "http://{$match[0]}";

        return '<a href="' . $completeUrl . '">'
            . $match[2] . $match[3] . $match[4] . '</a>';
    }

    function makeLink($string)
    {
        $rexProtocol = '(https?://)?';
        $rexDomain   = '((?:[-a-zA-Z0-9]{1,63}\.)+[-a-zA-Z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
        $rexPort     = '(:[0-9]{1,5})?';
        $rexPath     = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
        $rexQuery    = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $newString = "";

        $newString = $newString . preg_replace_callback("&\\b$rexProtocol$rexDomain$rexPort$rexPath$rexQuery$rexFragment(?=[?.!,;:\"]?(\s|$))&", 'callback', htmlspecialchars($string));

        //$matches = array();
        //$replacementURLs = array();
        //preg_match_all("/[a-z]+[:.].*?(?=\s)/i", $string, $matches);
		//print_r($matches);
		//$string = preg_replace("/([a-z]+[:.].*?(?=\s))/i", "<a target=\"_blank\" href=\"$1\">$1</a>", $string);
		//echo "<br>" . $string;
        /*foreach ($matches as $url) 
        {
            
        }

        //$string = preg_replace("(http|ftp|https):\/\/([\w\-_]+(?:(?:\.[\w\-_]+)+))([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?", replacement, subject);

        // make sure there is an http:// on all URLs
        $string = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2",$string);
        // make all URLs links
        $string = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a target=\"_blank\" href=\"$1\">$1</a>",$string);
        // make all emails hot links
        $string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>",$string);
		*/
        return $newString;
    }

    function getCurrentUserID()
    {
        return getID(getCurrentUsername());
    }

    function getNumUsers()
    {
        $sql = SQLCon::getSQL();
        return count($sql->sQuery("SELECT ID FROM UserData")->fetchAll());
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

    function canComment()
    {
        if (currentLogin() >= GROUP_REGISTERED)
            return true;
        return false;
    }

    function canUpload()
    {
        if (currentLogin() >= GROUP_REGISTERED)
            return true;
        return false;
    }
    
    /*
    *All this function does is if the user is logged in (as determined by the getCurrentUsername), we search for him in database to get his group.
    *But this also caches the group value so we dont have to go looking for it in the database again every time
    */
    function currentLogin()
    {
        if (isset($_SESSION['cachedUserGroup']) and !empty($_SESSION['cachedUserGroup']))
            return $_SESSION['cachedUserGroup'];
        incrementPerfCount("Need to cache current login value");
        $sql = SQLCon::getSQL();
        $user_check = getCurrentUsername();
        $result = $sql->sQuery("select Username, GroupVal from UserData where Username='$user_check'")->fetchAll(); //todo fix< bad //WHAT IS THIS COMMENT I LEFT?!?!?! WHY IS IT BAD
        //incrementPerfCount("CurrentLogin");
        if (!isset($result) or empty($result))
            return constant('GROUP_NONE');
        
        $login_session_username = $result[0]["Username"]; //this is the username that we looked up.
        
        if (empty($login_session_username) or !isset($login_session_username))
            return constant('GROUP_NONE');
        else
        {
            $_SESSION['cachedUserGroup'] = $result[0]["GroupVal"];
            $_SESSION['userGroupCachingTime'] = time();
            return $_SESSION['cachedUserGroup'];
        }
        return constant('GROUP_NONE');
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
	    $devicetype = $_SERVER['HTTP_USER_AGENT'];
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
            $stmt="INSERT INTO FileViews(File_ID, User_ID, IP, IPwithProxy, Device, ViewSource) VALUES ('$fileID', NULL, '$regIp','$proxIp','$devicetype', '$source');";
        else
            $stmt="INSERT INTO FileViews(File_ID, User_ID, IP, IPwithProxy, Device, ViewSource) VALUES ('$fileID', '$id', '$regIp','$proxIp','$devicetype', '$source');";

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
        $unhashedPassword = $password;
        if (!$alreadyHashed)
            $password = custHash($password);
        
        //$result = $sql->sQuery("SELECT ID FROM UserData WHERE Username='$username' AND Password='$password'")->fetchAll();

        $stmt = $sql->prepStmt("SELECT ID FROM UserData WHERE Username=:username AND Password=:password");
        $sql->bindParam($stmt, ":username", $username);
        $sql->bindParam($stmt, ":password", $password);
        $result = $sql->execute($stmt);
        if ($result)
            $result = $result->fetchAll();
        else
            $result = null;

        if (count($result) == 1)
        {
            logLogin($username, $password, true);
            $_SESSION['loggedInUsername'] = $username;
            $_SESSION['logInTime'] = time();
            return true;
        }
        else
        {
            logLogin($username, $password, false);
        }
        return false;
    }
    ?>
