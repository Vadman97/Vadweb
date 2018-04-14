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
        // both private and public channels
        $data = array('exclude_archived' => true);
        $ret = self::apiCall("https://slack.com/api/channels.list", $data)["channels"];
        return array_merge($ret, self::apiCall("https://slack.com/api/groups.list", $data)["groups"]);
    }

    public static function channelInvite($channelID, $userID) {
        $data = array(
            'channel' => $channelID,
            'user' => $userID,
        );
        return self::apiCall("https://slack.com/api/channels.invite", $data, $token=SlackToken::$USER_TOKEN);
    }

    public static function groupInvite($channelID, $userID) {
        $data = array(
            'channel' => $channelID,
            'user' => $userID,
        );
        return self::apiCall("https://slack.com/api/groups.invite", $data, $token=SlackToken::$USER_TOKEN);
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

if ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET["react"]) and $_GET["react"] == "nice")
{
    $targetRealName = "benelliott";
    $targetRealChannel = "botextensive";

    $benBotUserID = "loopdloop";

    $targetUser = null;
    foreach (Slack::getUsers()["members"] as $user) {
        if ($user["name"] == $targetRealName) {
            $targetUser = $user["id"];
            echo $targetUser . " " . $user["name"] . "\n<br>";
        }
        if ($user["name"] == $benBotUserID) {
            $benBotUserID = $user["id"];
        }
        if ($user["name"] == "widespread_nazareth") {
            echo "widespread_nazareth " . $user["id"] . "\n<br>";
        }
    }
    foreach (Slack::getChannels() as $channel) {
        print_r($channel["name"]); echo "\n<br>";
        if ($channel["name"] == $targetRealChannel) {
            echo $channel["name"] . "\n<br>";
            echo $channel["id"] . "\n<br>";
            echo $benBotUserID . "\n<br>";
            print_r(Slack::groupInvite($channel["id"], $benBotUserID));
            foreach (Slack::getChannelMessages($channel["id"]) as $message) {
                if ($message["user"] == $targetUser) {
//                    Slack::addMsgReaction($channel["id"], $message["ts"], "banana");
//                    Slack::addMsgReaction($channel["id"], $message["ts"], "nash");
//                    Slack::addMsgReaction($channel["id"], $message["ts"], "kishan");
//                    Slack::addMsgReaction($channel["id"], $message["ts"], "kissing_heart");
//                    Slack::addMsgReaction($channel["id"], $message["ts"], "eggplant");
//                    Slack::addMsgReaction($channel["id"], $message["ts"], "peach");
//                    Slack::addMsgReaction($channel["id"], $message["ts"], "heart");
//                    print_r($message);
                }
            }
        }
    }
}

?>
