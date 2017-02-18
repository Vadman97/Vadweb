<?php
class Slack {
    
    public static function messageChannel($channel, $text) {
        /*$data = "{
            \"token\": \"" . Slack::$BOT_TOKEN . "\",
            \"channel\": \"$channel\",
            \"text\": \"$text\"
        }";*/
        $data = array(
            'token' => SlackToken::$BOT_TOKEN,
            'channel' => $channel,
            'text' => $text,
            'username' => 'the_real_kishan',
            'as_user' => 'true'
        );


        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://slack.com/api/chat.postMessage");
        
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // $output contains the output string 
        $output = curl_exec($ch); 

        /*echo $data;
        print_r($data);
        echo $output;*/
        // close curl resource to free up system resources 
        curl_close($ch);
    }
}

//$s->messageChannel("#general", "sup dabs vadman of vadweb was here");
//Slack::messageChannel("@benelliott", "hey buddy");
//$s->messageChannel("@vkorolik", "hey buddy");
?>
