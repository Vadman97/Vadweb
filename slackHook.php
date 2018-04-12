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
    for ($log_file = "slack_logs/out_" . $i . ".log"; file_exists($log_file) && $i < 100; $i++);
    if ($i == 100) die();
    Slack::messageChannel($input["event"]["channel"], "hey!");
    file_put_contents($log_file, $inputJSON);
   
    if (!isset($input["event"]["bot_id"])) {
        // Slack::messageChannel($input["event"]["channel"], "And a " . $input["event"]["text"] . " to you as well!");
        // Slack::messageChannel($input["event"]["channel"], Slack::getRandomWordString(3));
    }
 
?>
