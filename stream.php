<?php
  require_once("htmlHead.php");
  require_once("util.php");
      /*if (!isLoggedIn()) {
              header("Refresh: 1; url= http://www.vadweb.us");
              echo "Please sign in. Redirecting to home page...";
              exit;
          }*/
          ?>
  <head>
     <link href="/resource/bootstrap/css/simpleTemp.css" rel="stylesheet">
     <title>Vadweb: File Uploads and Viewing</title>
     <?php
      logGenericPageView("files.php");
     ?>
     <?php
          if (isset($_GET["page"]))
          {
              if ($_GET["page"] > 1)
              {
                  echo '<link rel="prev" href="files.php?page=' . ($_GET["page"] - 1) . '">';
              }
              echo '<link rel="next" href="files.php?page=' . ($_GET["page"] + 1) . '">';
          }
          else
          {
              echo '<link rel="next" href="files.php?page=2">';
          }
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
          <li class="active"><a style="color:#FFF" href="/files.php">File Uploads</a></li>
          <li><a style="color:#FFF" href="/account.php">Account Settings</a></li>
        </ul>
        <?php printNavBarForms("files.php"); ?>
      </div><!--/.nav-collapse -->
    </div>
  </div>
  
  <div class="container-fluid">

    <div class="starter-template" >
      <h1>Our Loyal Sponsor: MukkaVukka</h1>
      <iframe src="http://www.twitch.tv/muckathevucka/embed" frameborder="0" scrolling="no" height="720" width="1080"></iframe><a href="http://www.twitch.tv/muckathevucka?tt_medium=live_embed&tt_content=text_link" style="padding:2px 0px 4px; display:block; width:345px; font-weight:normal; font-size:10px;text-decoration:underline;">Watch live video from muckathevucka on www.twitch.tv</a>
    </div>

  </div><!-- /.container -->
  <!-- Bootstrap core JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="/resource/jquery/jquery-2.1.1.min.js"></script>
  <script src="/resource/bootstrap/js/bootstrap.js"></script>
  <script src="files.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
 </body>
</html>