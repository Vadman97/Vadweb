<html>
    <head>
        <?php
            if (isset($title)) {
                $title = "- " . $title;
            } else {
                $title = "";
            }
        ?>
        <title> Vadweb <?php echo $title; ?> </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="format-detection" content="telephone=no">
        <meta name="keywords" content="files, images, funny pictures, image host, image upload, image sharing, image resize, file host, file upload, file sharing">
        <meta name="description" content="Vadweb is home to the webs most popular image and video content, curated in real time by a dedicated community through commenting, voting and sharing.">
        <meta name="copyright" content="Copyright 2014 Vadweb, SWAG.">
        <meta property="og:title" content="Vadweb File Sharing">
        <meta property="og:url" content="http://vadweb.us/files.php?">
        <meta property="og:description" content="Vadweb is home to the webs most popular image and video content, curated in real time by a dedicated community through commenting, voting and sharing.">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="X-UA-Compatible" content="IE=Edge;">

        <link rel="shortcut icon" href="images/vmg.ico">
        <link href="/resource/bootstrap/css/bootstrap.css" rel="stylesheet">
        <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet"> -->

        <link href="/resource/bootstrap/css/simpleTemp.css" rel="stylesheet">
        <script src="https://apis.google.com/_/scs/apps-static/_/js/k=oz.gapi.en.Po66YgTsIB4.O/m=gapi_iframes,gapi_iframes_style_common/rt=j/sv=1/d=1/ed=1/am=IQ/rs=AGLTcCMwzc7OePYK-UA9m-jQ8XGArpmA5Q/t=zcms/cb=gapi.loaded_0" async=""></script><script type="text/javascript" charset="UTF-8" src="https://apis.google.com/js/api.js" gapi_processed="true"></script>
    
        <?php 
            if (isset($header)) {
                echo $header; 
            }    
        ?> 
    </head>
    <body>

        <?php
            if (isset($content)) { 
                echo $content; 
            }
        ?>

        <script type="text/javascript" async="" src="https://www.gstatic.com/recaptcha/api2/r20150414130317/recaptcha__en.js"></script>
        <script src="/resource/jquery/jquery-2.1.1.min.js"></script>
        <script src="/resource/bootstrap/js/bootstrap.min.js"></script>
        <!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>-->
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <?php
            if (isset($footer)) {
                echo $footer;
            }
        ?>
    </body>

</html>
