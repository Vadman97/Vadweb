<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

function createTracker($to) {
	$sql = SQLCon::getSQL();
    $stmt = $sql->prepStmt("INSERT INTO EmailTracking (sent_to) VALUES (:to)");
    $sql->bindParam($stmt, ":to", $to);
    if ($sql->execute($stmt)) {
        $res = $sql->sQuery("SELECT id FROM EmailTracking ORDER BY timestamp DESC limit 1")->fetch();
        return $res[0];
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
	if (isset($_GET["to"])) {
        $trackerID = createTracker($_GET["to"]);
        if ($trackerID !== false) {
            $imgStr = '<img src="https://vadweb.us/emailTrack.php?id=';
            $imgStr .= $trackerID . '" width="1" height="1">';
            echo htmlspecialchars($imgStr);
            exit();
        }
    }
    echo "Error!";
}   

?>
