<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

echo '
    <style>
        table, th, td {
            text-align: center;
        }
    </style>
';

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

function getViews($id) {
    $sql = SQLCon::getSQL();
    $stmt = $sql->prepStmt("SELECT * FROM EmailViews WHERE email_track_id = :id ORDER BY timestamp DESC");
    $sql->bindParam($stmt, ":id", $id);
    $result = $sql->execute($stmt)->fetchAll();
    return $result;
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
	if (isset($_GET["to"])) {
        $trackerID = createTracker($_GET["to"]);
        if ($trackerID !== false) {
            $imgStr = '<img src="https://vadweb.us/emailTrack.php?id=';
            $imgStr .= $trackerID . '" width="1" height="1">';
            echo htmlspecialchars($imgStr);
            echo "<br>";
            echo '<a href="https://vadweb.us/emailTrackGenerate.php?check=' . $trackerID . '"> Track here </a>';
            exit();
        }
    } else if (isset($_GET["check"])) {
        $views = getViews($_GET["check"]);
        echo '<table style="width: 100%"';
        echo '<tr>
                <th> Timestamp </th>
                <th> IP </th>
                <th> Device </th>
              </tr>';
        foreach($views as $view) {
            printf("<tr> <td>%s</td> <td>%s</td> <td>%s</td> </tr>", $view[2], $view[3], $view[4]);
        }
    } else {
        echo "Error!";
    }
}   

?>
