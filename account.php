<?php
  require_once("util.php");
  if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
    if (!isLoggedIn())
    {
      header("Location: " . $_SERVER["HTTP_REFERER"]);
      exit();
    }
    $sql = SQLCon::getSQL();
    if (isset($_POST["email"]))
    {
      $id = getCurrentUserID();
      $newEmail = $_POST["email"];
      if (invalidEmail($newEmail))
      {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
      }
      if (emailVerified() === true)
      {
        //TODO ACTUALLY ALLOW EMAIL CHANGES, JUST MAKE IT SO YOU HAVE TO REVERIFY NEW EMAIL
        header("Location: " . $_SERVER["HTTP_REFERER"]);
        exit();
      }
      if ($sql->sQuery("UPDATE UserData SET Email='$newEmail' WHERE ID='$id'"))
      {
        ob_clean();
        unset($_SESSION['emailCode']);
        unset($_SESSION["emailResent"]);
        header("Location: " . $_SERVER["HTTP_REFERER"]);
      }
    }
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit();
  }
	require_once("htmlHead.php");
	//RIGHT HERE IS PROBABLY THE LINE TO DELETE
	//header("Refresh:2; URL=http://www.vadweb.us/troll.html");
?>
<head>
	<link href="/resource/bootstrap/css/simpleTemp.css" rel="stylesheet">
    <title>Account Settings</title>
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
            <li class="active"><a style="color:#FFF" href="/account.php">Account Settings</a></li>
          </ul>
          <?php printNavBarForms("account.php"); ?>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <div class="starter-template" >
        <h1>Account Settings</h1>
        <p class="lead" style="overflow:auto; overflow-style:marquee-block">Modify your account settings here.</p><br><br><br>
      	<h1 style='color:red; font-family: Comic Sans MS;'> much troll lolol no account settings yet sorry </h1>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/resource/jquery/jquery-2.1.1.min.js"></script>
	<script src="/resource/bootstrap/js/bootstrap.js"></script>
</body>
</html>
