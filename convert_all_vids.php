<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    require_once("dbcon.php");
    $sql = SQLCon::getSQL();
    $res = $sql->sQuery("select * from Files where Type = 'movie' and
                         Unlisted != 2 and File_ID > 822")->fetchAll();

    $bash_str = "#!/bin/bash<br>";
    foreach ($res as $movie) {
        $ext = "." . end(explode(".", $movie["FilePath"]));
        $name = substr($movie["FilePath"], 0, strpos($movie["FilePath"], "_conv"));

        $escapedInputFileName = "/home/vadwebData/" . str_replace(" ", "\ ", $name) . $ext;
        $escapedOutputFileName = "/home/vadwebData/" . str_replace(" ", "\ ", $name . "_conv_acc" . $ext);

        $bash_str .= "avconv -y -i " . $escapedInputFileName . "  -c:v libx264 -profile:v main -level 41 -pix_fmt yuv420p -crf 22 -maxrate 2M -bufsize 4M -preset medium -tune film -c:a aac -strict experimental -preset ultrafast -movflags +faststart " . $escapedOutputFileName . ";<br>";
        $bash_str .= "sudo chown www-data:www-data " . $escapedOutputFileName . ";<br>";
        $escapedOutputFileName = "/home/vadwebData/" . str_replace(" ", "\ ", $name . "_conv" . $ext);
        $bash_str .= "avconv -y -i " . $escapedInputFileName . "  -c:v libx264 -profile:v main -level 31 -pix_fmt yuv420p -crf 22 -maxrate 2M -bufsize 4M -preset medium -tune film -c:a libvorbis -qscale:a 9 -preset ultrafast -movflags +faststart " . $escapedOutputFileName . ";<br>";
        $bash_str .= "sudo chown www-data:www-data " . $escapedOutputFileName . ";<br>";
    }
    echo $bash_str;

       
?>
