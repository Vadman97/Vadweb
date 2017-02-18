<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_NOTICE);

class Post {
    var $author;
    var $category;
    var $content;
    var $updated;
    var $title;
}

function escapeAlexa($str) {
    //escape weird quotes and replace " with ' because json
    $str = preg_replace("~‘|’|“|”|\"~", "'", $str);
    //get rid of any other weird characters
    $str = preg_replace("/[^a-zA-Z0-9 \/,.:']/", "", $str);
    return $str;
}

function getPosts($subreddit = NULL) {
    $sub = "";
    if ($subreddit !== NULL)
        $sub = "r/$subreddit/";
    $x = simplexml_load_file("https://www.reddit.com/$sub.rss");
    $posts = array();
    foreach ($x->entry as $entry) {
        $post = new Post();
        $post->author = escapeAlexa((string) $entry->author->name);
        $post->category = escapeAlexa((string) $entry->category["label"]);
        $post->content = escapeAlexa((string) $entry->content);
        $post->updated = escapeAlexa((string) $entry->updated);
        $post->title = escapeAlexa((string) $entry->title);
        array_push($posts, $post);
    };
    return $posts;
}

?>
