<?php
    $time_start = microtime(true); 
	require_once("util.php");

	if (!isset($_GET["page"]) || (isset($_GET["page"]) && $_GET["page"] == 1))
		$page = 0;
	else
		$page = $_GET["page"] - 1;
	$numFiles = 20;
	$offset = $page * $numFiles;

    $sql = SQLCon::getSQL();
    //$userGroup = currentLogin();
    $userGroup = currentLogin();
    $currentUserID = getCurrentUserID();

    if ($page == -1)
    	$result = $sql->sQuery("SELECT File_ID, FilePath, User_ID, Type, CreatedTime, MinGroup, Unlisted, OtherPerms, NSFW, Description 
        FROM Files WHERE MinGroup <= '$userGroup' AND Unlisted = 0 OR User_ID = '$currentUserID' ORDER BY File_ID DESC")->fetchAll();
    else
    	$result = $sql->sQuery("SELECT File_ID, FilePath, User_ID, Type, CreatedTime, MinGroup, Unlisted, OtherPerms, NSFW, Description 
    	FROM Files WHERE MinGroup <= '$userGroup' AND Unlisted = 0 OR User_ID = '$currentUserID' ORDER BY File_ID DESC LIMIT " . $offset . "," . $numFiles)->fetchAll();
    if (count($result) == 0)
    {
    	ob_clean();
        header("HTTP/1.0 404 Not Found");
    	echo "-1";
    	exit();
    }
    $files = array();
    for ($i = 0; $i < count($result); $i++)
    {
    	$file = array();
        for ($j = 0; $j < count($result[0]); $j++) //j here is less than 5 because 5 column, 5 details from mysql 
        {
            $refOpen = "<a href='/view.php?name=".$result[$i][1]."'>";
            $refClose = "</a>";

                if ($j == 0)
                    array_push($file, $result[$i][0]);
                else if ($j == 1)
                    array_push($file, $refOpen . $result[$i][9] . $refClose);
                else if ($j == 2)
                {
                    if (in_array(getExtension($result[$i][1]), File::$pictureEXTs) && $result[$i][8] != 1)
                        array_push($file, $refOpen . "<img src='file.php?name=".htmlspecialchars($result[$i][1], ENT_QUOTES)."&amp;t' style='max-width:128px' alt='".htmlspecialchars($result[$i][9], ENT_QUOTES)." thumbnail'/>" . $refClose);
                    else if ($result[$i][8] == 1)
                        array_push($file, "Sp00ky NSFW");
                }
                else if ($j == 3) //this is to replace User_ID with the username from ID <<TODO FIND BETTER WAY TO DO THIS
                    array_push($file, getUsername($result[$i][2]));
                else if ($j == 4)
                    array_push($file, $result[$i][3]);
                else if ($j == 5)
                {
                    $completeFilePath = DEFAULT_FILE_STORAGE_PATH . $result[$i]["FilePath"];
                    $bytes = filesize($completeFilePath);
                    $decimals = 2;
                    $sz = 'BKMGTP';
                    $factor = floor((strlen($bytes) - 1) / 3);
                    array_push($file, sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor]);
                }
                else if ($j == 6)
                    array_push($file, $result[$i][4]);

        }   
        if ($result[$i][6] == "1")
            array_push($file, "unlisted");
    	array_push($files, $file);
    }
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start);
    //array_push($files, $execution_time);

    $json = json_encode($files, JSON_PRETTY_PRINT);
    ob_clean();
    header("Content-Type: application/javascript");
    echo $json;
?>