<?php
    require_once("slack.php");
    require_once("slackToken.php");

    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE); //convert JSON into array
    if ($input["token"] != SlackToken::$VERIFICATION) {
        // verification failed
         exit();
    }

    $i = 0;
    $log_file = "";
    do {
        $log_file = "slack_logs/out_" . $i . ".log";
        $i++;
    } while (file_exists($log_file) && $i < 100000);
    if ($i >= 100000) die();
    file_put_contents($log_file, $inputJSON);
   
    if (!isset($input["event"]["bot_id"])) {
        // Slack::messageChannel($input["event"]["channel"], "And a " . $input["event"]["text"] . " to you as well!");
         Slack::messageChannel($input["event"]["channel"], Slack::getRandomWordString(3));
    }
 
?>
