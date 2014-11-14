<?php
    require_once("htmlHead.php");
    require_once("dbcon.php");
    require_once("util.php");
    $filename = $_GET["name"];
    if (empty($filename))
        exit();
    if (strpos($filename, "'"))
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

    $nameArray = explode(".", $filename);
    $extension = strtolower(end($nameArray));
    $fileid = getFileID($filename);
    echo "<h3>View Count: " . count($sql->sQuery("SELECT View_ID FROM FileViews WHERE File_ID = '$fileid' AND ViewSource=1")->fetchAll()) . "</h3>";
    if ($result[0]["Type"] == File::$types["PICTURE"])
    {
        if (isset($_GET["r"]))
            echo "<a href='file.php?name=".$filename."&r'>";
        else
            echo "<a href='file.php?name=".$filename."'>";

        if (isset($_GET["r"]))
            echo "<img src='file.php?name=".$filename."&r' style='width: 100%;' alt='vadweb image upload creation user " . getUsername($result[0]["User_ID"]) . " quality'></img>"; 
        else
            echo "<img src='file.php?name=".$filename."' style='width: 100%;' alt='vadweb image upload creation user " . getUsername($result[0]["User_ID"]) . " quality'></img>"; 
        echo "</a>";
    }
    else if ($result[0]["Type"] == File::$types["MOVIE"])
    {
        echo '<video id="movie" src="file.php?name='.$filename.'" controls width="90%"></video>';
        //first figure out what file extension
        //then base on that to embed as different things
        /*if ($extension == "mov")
        {
            echo '<video id="sampleMovie" src="file.php?name='.$filename.'" controls></video>';
        }*/

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
        echo "<h2>Loading files other than images is currently being implemented.</h2>";
    echo "<h1>" . $filename . "</h1>";
    echo "<br><br><p><a href='file.php?name=".$filename."'>Click here for direct link to file " . $filename . ".</a></p>";
    ?>
</div>
</body>
