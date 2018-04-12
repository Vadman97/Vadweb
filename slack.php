<?php
require_once('slackToken.php');

class Slack {
    public static function apiCall($endpoint, $data, $token = null) {
        $data['token'] = $token == null ? SlackToken::$BOT_TOKEN : $token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // $output contains the output string
        $output = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($output, true);
        return $json;
    }
    
    public static function messageChannel($channel, $text) {
        $data = array(
            'channel' => $channel,
            'text' => $text,
            'username' => 'widespread_nazareth',
            'as_user' => 'true'
        );

        return self::apiCall("https://slack.com/api/chat.postMessage", $data);
    }

    public static function getRandomWordString($nWords) {
        $f = fopen("test.log", "r") or die("Cannot open");
        $file = fread($f, filesize("test.log"));
        $f_arr = explode(" ", $file);
        shuffle($f_arr);
        $c = 0;
        $msg = "";
        foreach($f_arr as $word) {
            if ($c >= $nWords) {
                break;
                $c = 0;
                $msg = "";
            }
            $msg .= $word . " ";
            $c += 1;
        }
        fclose($f);
        return $msg;
    }

    public static function getChannelMessages($channelID) {
        $data = array(
            'channel' => $channelID,
            'limit' => 100
        );

        $resp = null;
        $messages = array();
        while ($resp == null or ($resp["response_metadata"] and $resp["response_metadata"]["next_cursor"])) {
            if ($resp != null) {
                $data["cursor"] = $resp["response_metadata"]["next_cursor"];
            }
            $resp = self::apiCall("https://slack.com/api/conversations.history", $data, $token=SlackToken::$USER_TOKEN);
            $messages = array_merge($messages, $resp["messages"]);
        }
        return $messages;
    }

    public static function getChannels() {
        $data = array('exclude_archived' => true);

        return self::apiCall("https://slack.com/api/channels.list", $data);
    }

    public static function getUsers() {
        return self::apiCall("https://slack.com/api/users.list", array());
    }

    public static function addMsgReaction($channelID, $msgTimestamp, $reactName) {
        $data = array(
            'channel' => $channelID,
            'name' => $reactName,
            'timestamp' => $msgTimestamp
        );
        return self::apiCall("https://slack.com/api/reactions.add", $data);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
    $targetRealName = "kishan";
    $targetRealChannel = "general";

    $targetUser = null;
    foreach (Slack::getUsers()["members"] as $user) {
        if ($user["name"] == $targetRealName) {
            $targetUser = $user["id"];
            echo $targetUser . " " . $user["name"] . "\n<br>";
        }
    }
    foreach (Slack::getChannels()["channels"] as $channel) {
        if ($channel["name"] == $targetRealChannel) {
            echo $channel["name"] . "\n<br>";
            echo $channel["id"] . "\n<br>";
            foreach (Slack::getChannelMessages($channel["id"]) as $message) {
                if ($message["user"] == $targetUser) {
                    Slack::addMsgReaction($channel["id"], $message["ts"], "thumbsup");
                    Slack::addMsgReaction($channel["id"], $message["ts"], "nash");
                    Slack::addMsgReaction($channel["id"], $message["ts"], "kishan");
                    Slack::addMsgReaction($channel["id"], $message["ts"], "kissing_heart");
                    Slack::addMsgReaction($channel["id"], $message["ts"], "eggplant");
                    Slack::addMsgReaction($channel["id"], $message["ts"], "peach");
                    Slack::addMsgReaction($channel["id"], $message["ts"], "heart");
                    print_r($message);
                }
            }
        }
    }
}

?>
