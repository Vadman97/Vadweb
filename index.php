<?php
require_once("htmlHead.php");
require_once("dbcon.php");
require_once("util.php");
?>
<head>
  <title>Vadweb Overview Home Page</title>
  <meta name="robots" content="index"/>
  <link href="resource/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <?php
    logGenericPageView("index.php");
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
        <a class="navbar-brand" href="#">Vadweb</a>
      </div>        
      
      <div class="navbar-collapse collapse">

       <ul class="nav navbar-nav">
        <li><a style="color:#FFF" href="/register.php">Register</a></li>
        <li><a style="color:#FFF" href="/files.php">File Uploads</a></li>
        <li><a style="color:#FFF" href="/account.php">Account Settings</a></li>
      </ul>
      
      <?php printNavBarForms(); ?>
      
      </div>
    </div><!--/.navbar-collapse -->
  </div>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <br />
    <h1>Welcome to Vadweb!</h1><br>
    <h2>Quick, easy, secure file sharing.</h2><br><br><br>

    
    <?php
    
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    $sql = SQLCon::getSQL();
    $sql->configTables();
    
    ?>
    
    <p>Need an account? Make one to upload files and improve your viewing experience!</p>
    <p><a class="btn btn-primary btn-lg" role="button" href="/register.php">Click here to register! »</a><a type="button" href="/about.php" class="btn btn-lg btn-success" style="margin-left:5em">About this project</a></p>
    <br>
    <br>
    <p><a class="btn btn-info btn-lg" role="button" href="/files.php">File Uploads »</a></p>
  </div>
</div>

<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-4">
      <h2>Vadweb 2.0, now hundreds of times faster</h2>
      <p>Using the latest technologies delivered by MySQL and PHP 5.5.x, Vadweb loads files at blazing speeds, even when calculating and sorting through complex permission settings.  </p>
      <p><a class="btn btn-default" href="/about.php" role="button">View details »</a></p>
    </div>
    <div class="col-md-4">
      <h2>Share your files and images securely</h2>
      <p>Have you needed to send your friend a picture, but only to find its larger than 25 megabytes? Have you wanted to send your friend a video, but are too lazy to upload it to YouTube, or want to share in true high definition? Have you wanted to share a file with your grandmother to help her install or configure her computer? Whether for family needs or for expressing yourself to the entire internet, VadWeb answers all of your legal file sharing needs. </p>
      <p><a class="btn btn-default" href="/about.php" role="button">View details »</a></p>
    </div>
    <div class="col-md-4">
      <h2>Featuring more AWESOME</h2>
      <p>Finer user account control, profile pictures, and an advanced commenting/voting system permit you to have a pinnacle experience as you interact with others.</p>
      <p><?php echo count($sql->sQuery("select * from UserData")->fetchAll());?> users registered.</p>
      <p>User group: <?php echo currentLogin()?>.</p>
      <p><a class="btn btn-default" href="/about.php" role="button">View details »</a></p>
    </div>
  </div>
  
  <div class="container" <?php if (currentLogin() < GROUP_ADMIN) echo 'hidden="hidden"';?>>
   Welcome admin!
 </div>

 <hr>

 <footer>
  <p>Please be aware that some features are in beta testing or in development, and may not be available for <u>all</u> users</p>
  <p><b>© Vadweb 2014-2015</b></p>
</footer>
</div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/resource/jquery/jquery-2.1.1.min.js"></script>
    <script src="/resource/bootstrap/js/bootstrap.js"></script>
    

  </body>


  </html>