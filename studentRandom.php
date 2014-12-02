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

  <title>Vadweb: Student Randomizer</title>
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
      </div><!--/.nav-collapse -->
    </div>
  </div>
  <div class="container-fluid">
    <div class="starter-template" >
      <h3>Random student</h3>
<?php
	$sql = SQLCon::getSQL();
	lel:
	$result = $sql->sQuery("SELECT * FROM Students WHERE SelectedTimes=0")->fetchAll();
	if (count($result) == 0)
	{
		$sql->sQuery("UPDATE Students SET SelectedTimes=0");
		goto lel;
	}
	$random = rand(0, count($result) - 1);
	echo "<h1>".$result[$random][1]."</h1><br>";
	//echo $random."<br>";
	//echo count($result)."<br>";
	$sql->sQuery("UPDATE Students SET SelectedTimes=SelectedTimes+1 WHERE ID=" . $result[$random][0]);

?>	
    </div>
  </div>
</body>
</html>
