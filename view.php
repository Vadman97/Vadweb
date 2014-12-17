<?php
    $time_start = microtime(true); 
    require_once("htmlHead.php");
    require_once("dbcon.php");
    require_once("util.php");
    $filename = $_GET["name"];
    if (empty($filename))
        exit();
    if (canViewFileByName($filename, VIEWING_MODE)) 
    {
        $sql = SQLCon::getSQL();
        $stmt = $sql->prepStmt("SELECT * FROM Files WHERE FilePath = :file");
        $sql->bindParam($stmt, ":file", $filename);
        $result = $sql->execute($stmt);
        if (!$result)
        {
            $result = null;
            exit();
        }
        $result = $result->fetchAll();
        headForView($result);
        $title = $result[0]["Description"] . " - Vadweb File Sharing View";
    }
    else
    {
        $result = null;
        $title = "Vadweb File Sharing View";
    }

    function echoMessage($message)
    {
        echo $message;
        echo '<div class="row-fluid" style="padding-top:5px">';
        echo '<button class="btn btn-xs btn-primary">Reply</button>';
        echo '</div>';
    }
    function openMessage($message)
    {
        echo '
        <div class="container-fluid" style="padding-top:10px;">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-11">
                    <div class="well well-sm">
        ';
        echoMessage($message);
    }
    function closeMessage()
    {
        echo '
                    </div>
                </div>
            </div>
        </div>
        ';
    }
    function openRootMessage($message)
    {
        echo '<div class="well well-sm">';
        echoMessage($message);
    }
    function closeRootMessage()
    {
        echo '</div>';
    }
    function runSublayer($superComment, $fileid)
    {
        $sql = SQLCon::getSQL();
        $result = $sql->sQuery("SELECT * FROM Comments WHERE File_ID = '$fileid' && SubCommentOf=$superComment")->fetchAll();
        $resultNum = count($result);
        if ($resultNum == 0)
            return;
        for ($i = 0; $i < $resultNum; $i++)
        {
            openMessage($result[$i]["Comment"]);
            runSublayer($result[$i]["ID"], $fileid);
            closeMessage();
        }
    }
?>
<head>
    <title><?php echo $title; ?></title>
    <link href="/resource/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="/resource/bootstrap/css/simpleTemp.css" rel="stylesheet">
    <link rel="image_src" type="image/jpeg" href=<?php echo '"/file.php?name=' . $result[0]["FilePath"] . '"'; ?>>
</head>

<body style='padding-top:65px;'>

    <!--
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="form">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div>
      </div>
    </nav>
    -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">
              <div class="navbar-header">
                 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Vadweb</a>
            </div>             
            <div id="navbar" class="navbar-collapse navbar-responsive-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a style="color:#FFF" href="/register.php">Register</a></li>
                    <li><a style="color:#FFF" href="/files.php">File Uploads</a></li>
                    <li><a style="color:#FFF" href="/account.php">Account Settings</a></li>
                </ul>
                <?php printNavBarForms("view.php"); ?>
            </div>
        </div><!--/.navbar-collapse -->
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7" name="fileBody" id="fileBody">
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
                if ($result[0]["Type"] == File::$types["PICTURE"])
                {
                    if (isset($_GET["r"]))
                        echo "<a href='file.php?name=".$filename."&r'>";
                    else
                        echo "<a href='file.php?name=".$filename."'>";

                    if (isset($_GET["r"]))
                        echo "<img src='file.php?name=".$filename."&r' style='width: 100%;' alt='" . htmlspecialchars($result[0]["Description"], ENT_QUOTES) . " image upload creation user " . htmlspecialchars(getUsername($result[0]["User_ID"]), ENT_QUOTES) . " quality'></img>"; 
                    else
                        echo "<img src='file.php?name=".$filename."' style='width: 100%;' alt='" . htmlspecialchars($result[0]["Description"], ENT_QUOTES) . " image upload creation user " . htmlspecialchars(getUsername($result[0]["User_ID"]), ENT_QUOTES) . " quality'></img>"; 
                    echo "</a>";
                }
        		else if ($result[0]["Type"] == File::$types["PDF"])
        		{
        			echo '<iframe src="file.php?name=' . $filename  . '" style="width:100%;" frameborder="0"></iframe>';
        		}
                else if ($result[0]["Type"] == File::$types["MOVIE"])
                {
                    echo '<video id="movie" src="file.php?name='.$filename.'" controls width="100%"></video>';
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
                {
                    if ($extension == "txt")
                    {
                        echo "<script>";
                        echo "

                        ";
                        echo "</script>";
                    }
                    else
                    {
                        echo "<p><font size='6'>File cannot be previewed. Please click the 'direct link' to download.</font></p>";
                    }
                }
                ?>
            </div>
            <div class="col-md-5">
                <?php
                echo "<h1>" . htmlspecialchars($result[0]["Description"], ENT_QUOTES) . "</h1>";
                echo "<h2>" . $filename . "</h2>";
                echo "<h3>Views: " . count($sql->sQuery("SELECT View_ID FROM FileViews WHERE File_ID = '$fileid' AND ViewSource=1")->fetchAll()) . "</h3>";
                echo "<p><a href='file.php?name=".$filename."&r'>Click here for direct link to file " . $filename . ".</a></p><br>";

                $result = $sql->sQuery("SELECT * FROM Comments WHERE File_ID = '$fileid' && SubCommentOf IS NULL")->fetchAll();
                $rootComm = count($result);
                for ($i = 0; $i < $rootComm; $i++)
                {
                    openRootMessage($result[$i]["Comment"]);
                    runSublayer($result[$i]["ID"], $fileid);
                    closeRootMessage();
                    //$subCommentTested = $result[$i][0];
                    //$result2 = $sql->sQuery("SELECT * FROM Comments WHERE File_ID = '$fileid' && SubCommentOf = '$subCommentTested'")->fetchAll();
                    //this would have another nested loop for number of count above, do the same thing where subCommentOf = result2[$i][0];
                    //problem with this is you don't know how deep to go overall...
                }
                $time_end = microtime(true);
                $execution_time = ($time_end - $time_start);
                gc_enable();
                echo '<p><b>Total Execution Time:</b> '.$execution_time.' Sec</p><br><br>';
                ?>


                <ul class="media-list">
                  <li class="media">
                    <a class="media-left" href="#">
                      <img data-src="holder.js/64x64" alt="64x64" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PGRlZnMvPjxyZWN0IHdpZHRoPSI2NCIgaGVpZ2h0PSI2NCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjE0LjUiIHk9IjMyIiBzdHlsZT0iZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQ7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+NjR4NjQ8L3RleHQ+PC9nPjwvc3ZnPg==" data-holder-rendered="true" style="width: 64px; height: 64px;">
                    </a>
                    <div class="media-body" style="width:100%">
                      <h4 class="media-heading">Post a comment</h4>
        		     	<form role="form" method="post" enctype="multipart/form-data" action="submitComment.php">
        				  <div class="form-group"> 
        		   		    <input type="text" id="comment" name="comment" class="form-control" placeholder="Comment here" style="width:100%">
                   	 	  </div>
        				  <input type="text" id="filename" name="filename" hidden="hidden" value="<?php echo $filename; ?>">
        			      <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                  </li>
                </ul>


            </div>
        </div>
    </div>
    <script src="/resource/jquery/jquery-2.1.1.min.js"></script>
    <script src="/resource/bootstrap/js/bootstrap.js"></script>
</body>
