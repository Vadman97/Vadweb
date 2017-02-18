<?php
$start_time = microtime(true);
require_once "util.php";
require_once "redditParse.php";
require_once "slack.php";
$sql = SQLCon::getSQL();
ob_clean();

function speakRedditPosts($sub = NULL) {
    //TODO customize for subreddit
    $response = " Here are the top reddit posts from ";
    $response .= $sub === NULL ? "the homepage" : $sub;
    $response .= ".";
    $posts = getPosts($sub);

    $counter = 1;
    foreach($posts as $post) {
        if ($counter == 6)
            return $response;
        $response .= " Number $counter: ";
        $response .= $post->title . ".";
        if ($sub === NULL) {
            $response .= "The post is from " . $post->category;
            //$response .= " and was uploaded by " . $post->author;
            $response .= ".";
        }
        //TODO print post content if it's text
        //TODO extra dots if the title already has a dot at the end
        $counter++;
    }

    return $response;
}

header("HTTP/1.1 200 OK");
header("Content-Type: application/json;charset=UTF-8");
header("Content-Length:");

$data = json_decode(file_get_contents('php://input'), true);

$target = strtolower($data["request"]["intent"]["slots"]["person"]["value"]);
$michaelText = '<speak>I think that Michael Kazuki Morikado is a really kawaii half-asian boy. I really love how he says my name. <audio src=\"https://vadweb.us/file.php?name=jina.mp3\"/> It really turns me on... In fact, I will send him an email right now to remind him how much I love him.';
$defaultText = '<speak>Not sure I know ' . $target . ', sorry. I may have a lot of friends but not that many.';
$benText = '<speak>Ah Benjamin. If only he would consider me his friend. Ghandi Puvada Puuuuuuuuuuuuuuuuuuuuuuuuuuuvyyyyyyyyyyyyyyyyyyyyy puvy puvy puvy puvy Puvy puvyyy puuuuvvyyyy.';
$dillonText = '<speak>Dillon has a huuuuuuuuuuuuuuuuge cock. Like the penis of a horse. No, bigger. <say-as interpret-as=\"spell-out\">Peeeeeeeeeeeenis</say-as> is how long it is.';
$vadimText = '<speak>Loading information about vadweb<say-as interpret-as=\"spell-out\">.us</say-as>. There are %d users registered. There are %d files uploaded. The last file that was uploaded is called %s with filename %s. It was uploaded on %s.';
$randomText = '<speak>laa ';
for ($i = 0; $i < 10; $i++) {
  $randomText .= 'laa';
  for ($j = 0; $j < rand() % 25; $j++)
    $randomText .= 'ahoeoaeoi';
  $randomText .= ' ';
}
$randomText .= '.';

$users = $sql->sQuery("select count(*) from UserData")->fetchAll()[0][0];
$files = $sql->sQuery("select count(*) from Files")->fetchAll()[0][0];
$newFile = $sql->sQuery("select FilePath, Description, CreatedTime from Files " .
			"order by CreatedTime desc limit 1")->fetchAll()[0];
$vadimText = sprintf($vadimText, $users, $files, $newFile[1], $newFile[0], date('l Y-m-d \a\t h:i A', strtotime($newFile[2])));

$text = $defaultText;
if ($target == "michael") {
  $text = $michaelText;
  emailAnyString("<3 <3 <3 much love <3 <3 <3 - Jina", "Reminder of my love! " . rand(), "morikado@usc.edu");
  Slack::messageChannel("#general", "Michael, I love your tight smooth big booty <3<3<3");
} else if ($target == "ben") {
  $text = $benText;
  $text .= speakRedditPosts("oldladiesbakingpies");
} else if ($target == "dillon" || $target == "dylan" || $target == "dilon") {
  $text = $dillonText;
} else if ($target == "vadim" || $target == "vladimir") {
  $text = $vadimText;
  $text .= speakRedditPosts();
} else if ($target == "bob") {
  $text = $randomText;
  $text .= speakRedditPosts("ooer");
}

$time = microtime(true) - $start_time;
$text .= sprintf(" This call took %.3f seconds to execute</speak>", $time);

echo '{
  "version": "1.0",
  "sessionAttributes": {
  },
  "response": {
    "outputSpeech": {
      "type": "SSML",
      "ssml": "' . $text . '"
    },
    "shouldEndSession": true
  }
}';
