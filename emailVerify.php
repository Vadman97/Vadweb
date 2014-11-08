<?php
ob_clean();
require_once("util.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="format-detection" content="telephone=no"/>
  <meta name="keywords" content="files, images, funny pictures, image host, image upload, image sharing, image resize, file host, file upload, file sharing, email validation, user registration"/>
  <meta name="description" content="Vadweb is home to the webs most popular image and video content, validate your email to enchance your user experience."/>
  <meta name="copyright" content="Copyright 2014 Vadweb, SWAG."/>

  <meta http-equiv="X-UA-Compatible" content="IE=Edge;"/>

  <link rel="shortcut icon" href="images/vmg.ico"/>
  <link href="/resource/bootstrap/css/bootstrap.css" rel="stylesheet"/>
  <link href="/resource/bootstrap/css/simpleTemp.css" rel="stylesheet">

  <title>Vadweb: Registering Email Validation</title>
  <?php
  logGenericPageView("emailVerify.php");
  ?>
</head>

<body>
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Vadweb</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li><a style="color:#FFF" href="/register.php">Register</a></li>
          <li><a style="color:#FFF" href="/files.php">File Uploads</a></li>
          <li><a style="color:#FFF" href="/account.php">Account Settings</a></li>
        </ul>
        <?php printNavBarForms("emailVerify.php"); ?>
      </div><!--/.nav-collapse -->
    </div>
  </div>
  <div class="container-fluid">
    <?php

    if ($_SERVER['REQUEST_METHOD'] == "GET")
    {
      $code = $_GET["c"];
      if ($code == $_SESSION['emailCode'])
      {
        echo "<p><h1> Success: you have successfully validated. You will now be redirected.</h1></p>";
            //validate
        exit();
      }
    }
    if (!isLoggedIn())
    {
      echo "<h1>ERROR: You are not logged in... </h1>";
      exit();
    }
    if (!isset($_SESSION['emailCode']))
    {
      $_SESSION['emailCode'] = generateRandomLetterString(20);
      emailString($_SESSION['emailCode']);
      echo "<p><h2>Your email verification code has been emailed. Please follow the instructions in the email.</h2><br>You will be able to access site features once you click the link in the email.</p>";
    }
    else
    {
        //click here to request another email
        //button to change email if it is wrong
        //timeouts for all this so cannot spam :(
      emailString($_SESSION['emailCode']);
      echo "<p><h2>Your email verification code has been emailed. Please follow the instructions in the email.</h2><br>You will be able to access site features once you click the link in the email.</p>";
    }
    ?>
  </div>
</body>
</html>