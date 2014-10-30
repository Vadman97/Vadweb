<?php
    require_once("htmlHead.php");
    require_once("dbcon.php");
    require_once("util.php");
    $filename = $_GET["name"];
    if (empty($filename))
        exit();
    if (canViewFileByName($filename, VIEWING_MODE)) 
    {
        $sql = SQLCon::getSQL();
        $result = $sql->sQuery("SELECT * FROM Files WHERE FilePath = '$filename'")->fetchAll();
        headForView($result);
    }
    else
    {
        $result = null;
    }
?>
<head>
    <title>Vadweb: File View - <?php echo $result[0]["FilePath"];?></title>
    <link href="resource/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link rel="image_src" type="image/jpeg" href=<?php echo '"/file.php?name=' . $result[0]["FilePath"] . '"'; ?>>
</head>

<body style='padding-top:65px;'>

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
        <?php printNavBarForms("register.php"); ?>
    </div>
</div><!--/.navbar-collapse -->
</div>
</div>
<div class="container" >
    <?php
    if (!canViewFileByName($filename, VIEWING_MODE)) 
    {
        echo "<h1 style='color:red; font-family: Comic Sans MS'>WARNING NO PERMISSION TO VIEW </h1><br>";
        if (!isLoggedIn())
            echo "<h1 style='color:red; font-family: Comic Sans MS'>YOU ARE NOT SIGNED IN </h1><br>";
        exit;
    }

    logFileView($result[0]["File_ID"], NULL, NULL, VIEW_PHP);

    if ($result[0]["Type"] == File::$types["PICTURE"])
    {
        echo "<a href='file.php?name=".$filename."'>";
        echo "<img src='file.php?name=".$filename."' style='width: 100%;'></img>"; 
        echo "</a>";
    }
    else if ($result[0]["Type"] == File::$types["FLASH"])
    {
        echo    "<object type='application/x-shockwave-flash' 
                  data='file.php?name=".$filename."'
                  width='100%' height='600'>
                  <param name='movie' value='your-flash-file.swf' />
                  <param name='quality' value='high'/>
                  <p>You need flash to view this file</p>
                </object>";
    }
    else
        echo "<h1>Loading files other than images is currently being implemented.</h1>";
    echo "<br><br><p><a href='file.php?name=".$filename."'>Click here for direct link.</a></p>";
    ?>
</div>
</body>
