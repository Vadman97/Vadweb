<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

function validateID($id) {
	$sql = SQLCon::getSQL();
    $stmt = $sql->prepStmt("SELECT COUNT(*) FROM EmailTracking WHERE id=:id");
    $sql->bindParam($stmt, ":id", $id);
    $count = $sql->execute($stmt)->fetch()[0];
    if ($count == 1) {
        return true;
    }
    return false;
}

function recordView($id) {
	$sql = SQLCon::getSQL();
    $stmt = $sql->prepStmt("INSERT INTO EmailViews (email_track_id, ip, device) VALUES (:id, :ip, :device)");
    $sql->bindParam($stmt, ":id", $id);
    if (isset($_SERVER['REMOTE_ADDR']))
        $sql->bindParam($stmt, ":ip", $_SERVER['REMOTE_ADDR']);
    if (isset($_SERVER['HTTP_USER_AGENT']))
        $sql->bindParam($stmt, ":device", $_SERVER['HTTP_USER_AGENT']);

    $sql->execute($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
	if (isset($_GET["id"]) && validateID($_GET["id"])) {
        recordView($_GET["id"]);
    }
    $path = "/home/vadwebData/white_pic.jpg";
    $filesize = filesize($path);
    ob_clean();
    header("Content-Type: image/jpeg");
    header("Content-Length: '$filesize'");
    header('Expires: 0');                                                       
    header('Cache-Control: must-revalidate');                                   
    header('Pragma: public');                  
    $img = imagecreatefromjpeg($path);
    imagejpeg($img, NULL, 30);
    exit();
}

?>
