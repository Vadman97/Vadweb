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
    <h1>File Uploads</h1>
    <p class="lead" style="overflow:auto; overflow-style:marquee-block">View and upload files here! <br><br> <span hidden>Warning: Some features may be innaccessible as the website <br> is in the state of rewriting, or if you are not an approved user. <br><br></span>
    <span style='color:red; font-family: Comic Sans MS' hidden> Notice: You may experience issues with logging in. Vadweb is currently being modified with a major fix for the issue. <br> Thank you for your patience and understanding!</span>
    </p>
    <?php
        //echo apc_fetch("newSQLPointer") . "<br>";
        //echo apc_fetch("cachedSQLPointer") . "<br>";
    ?>
    <div class="col-md-12" style="padding-left:0px;">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="margin-left:0px;">
                Upload
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="margin-left:0px;" data-toggle="modal">
                <li><a href="#" data-toggle="modal" data-target="#upload-single">Upload Single File</a></li>
                <!--<li><a href="#" data-toggle="modal" data-target="#upload-multi">Upload Multiple Files</a></li>-->
            </ul>
        </div>
    </div>
</div>

    <div class="main"><!--
      <class="page-header" hidden="hidden">Dashboard</h1>

      <div class="row placeholders" hidden="hidden">
        <div class="col-xs-6 col-sm-3 placeholder">
          <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iIzBEOEZEQiIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojZmZmO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=">
          <h4>Label</h4>
          <span class="text-muted">Something else</span>
      </div>
      <div class="col-xs-6 col-sm-3 placeholder">
          <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iIzM5REJBQyIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojMUUyOTJDO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=">
          <h4>Label</h4>
          <span class="text-muted">Something else</span>
      </div>
      <div class="col-xs-6 col-sm-3 placeholder">
          <img data-src="holder.js/200x200/auto/sky" class="img-responsive" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iIzBEOEZEQiIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojZmZmO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=">
          <h4>Label</h4>
          <span class="text-muted">Something else</span>
      </div>
      <div class="col-xs-6 col-sm-3 placeholder">
          <img data-src="holder.js/200x200/auto/vine" class="img-responsive" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iIzM5REJBQyIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojMUUyOTJDO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=">
          <h4>Label</h4>
          <span class="text-muted">Something else</span>
      </div>
    </div>-->

    <h2 class="sub-header">View Files! <span <?php if (isLoggedIn()) echo 'hidden="hidden"'; ?> style='color:red; font-family: Comic Sans MS'> Warning - Not signed in. You might be missing out...</span></h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>File #</th>
                        <th>File Name</th>
                        <th>Preview</th>
                        <th>User</th>
                        <th>File Type</th>
                        <th>File Size</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody id="fileTable">
					<tr><td><b>
						Loading...
					</b></td></tr>
                    <?php
                        /*$time_start = microtime(true); 
                        $sql = SQLCon::getSQL();
                        //$userGroup = currentLogin();
                        $userGroup = currentLogin();
                        $result = $sql->sQuery("SELECT File_ID, FilePath, User_ID, Type, CreatedTime, MinGroup, Unlisted, OtherPerms, NSFW, Description FROM Files WHERE MinGroup <= '$userGroup' AND Unlisted = 0 ORDER BY File_ID DESC")->fetchAll();
                        for ($i = 0; $i <= count($result) - 1; $i++)
                        {
                            echo "<tr>\n";
                            for ($j = 0; $j < 7; $j++) //j here is less than 5 because 5 column, 5 details from mysql 
                            {
                                $refOpen = "<a href='/view.php?name=".$result[$i][1]."'>";
                                $refClose = "</a>";

                                echo "\t\t\t<td>";
                                    if ($j == 0)
                                        echo $result[$i][0];
                                    else if ($j == 1)
                                        echo $refOpen . $result[$i][9] . $refClose;
                                    else if ($j == 2)
                                    {
                                        if (in_array(getExtension($result[$i][1]), File::$pictureEXTs) && $result[$i][8] != 1)
                                            echo $refOpen . "<img src='file.php?name=".htmlspecialchars($result[$i][1], ENT_QUOTES)."&amp;t' style='max-width:128px' alt='".htmlspecialchars($result[$i][9], ENT_QUOTES)." thumbnail'/>" . $refClose;
                                        else if ($result[$i][8] == 1)
                                            echo "Sp00ky NSFW";
                                    }
                                    else if ($j == 3) //this is to replace User_ID with the username from ID <<TODO FIND BETTER WAY TO DO THIS
                                        echo getUsername($result[$i][2]);
                                    else if ($j == 4)
                                        echo $result[$i][3];
                                    else if ($j == 5)
                                    {
                                        $completeFilePath = DEFAULT_FILE_STORAGE_PATH . $result[$i]["FilePath"];
                                        $bytes = filesize($completeFilePath);
                                        $decimals = 2;
                                        $sz = 'BKMGTP';
                                        $factor = floor((strlen($bytes) - 1) / 3);
                                        echo sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
                                    }
                                    else if ($j == 6)
                                        echo $result[$i][4];

                                echo "</td>\n";
                            }   
                            echo "\t\t</tr>\n";
                        }
                        $time_end = microtime(true);
                        $execution_time = ($time_end - $time_start);*/
                    ?>
                </tbody>
            </table>
        </div>
        <?php 
            echo '<p id="execTime"><b>Total Execution Time:</b> '.$execution_time.'</p>';
        ?>
        <p>Interested in how this page loads? Click here: <a href="getFiles.php?page=0" > http://vadweb.us/getFiles.php?page=0 </a></p>
    </div>


    <div class="modal fade" id="upload-single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel2">Upload a single file</h4>
                </div>
                <div class="modal-body">
                    <form method="post" role="form" id="singleUploadForm" enctype="multipart/form-data" action="fileUpload.php" autocomplete="off">
                    <div class="container-fluid form">
                        <h5> This feature works for all registered users. </h5>
                        <h5> Maximum file size is <?php echo FILE_SIZE_LIMIT/1000/1000/1000; ?> GB. </h5>
                        <div class="well row">
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label for="fileSingle">Single File:</label><br>
                                    <input class="form-control" type="file" name="fileSingle" required>
                                </div> 
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label for="fileDesc">File Description / Title:</label><br>
                                    <textarea class="form-control" form="singleUploadForm" name="fileDesc" placeholder="Description" maxlength=300 required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="well row" >
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <b><span class="control-label">Who can view?</span></b><br>
                                            <input type="radio" id="perm" name="perm" value="<?php echo GROUP_NONE; ?>" checked="checked"> Everyone<br>
                                            <input type="radio" id="perm" name="perm" value="<?php echo GROUP_REGISTERED; ?>"> Registered<br>
                                            <input type="radio" id="perm" name="perm" value="<?php echo GROUP_FRIENDS; ?>"> VIP Friends<br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <b><span class="control-label">Unlisted File?</span></b><br>
                                            <input class="form-control" type="checkbox" name="unlisted" id="unlisted" value="unlisted">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><span class="control-label">User specific:</span></b><br>
                                    <b><span class="control-label">Coming soon</span></b><br>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding-top:50px" hidden>
                            <!-- Captcha-->
                            <div class="col-sm-12">
                                <div class="form-group">
                                  <div class="col-md-4">
                                    <div class="g-recaptcha" data-sitekey="6LeTUf4SAAAAAJ6U9O9s0W6jcr9wPiJgqW60bwWh"></div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="upload-submit"></label><br><br><br>
                            <input id="upload-submit" class="btn btn-default" type="submit" value="Submit File" style="margin-top:5px;"> 
                        </div>
                    </div>
                    </form>                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal fade -->

    <div class="modal fade" id="upload-multi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel2">Upload multiple files</h4>
                </div>

                <div class="modal-body">
                    <h5> This feature is only accessible to admins.</h5>
                    <h5> You may only upload <?php echo MULTI_FILE_UPLOAD_NUM_LIMIT; ?> files at a time.</h5>
                    <h5> Just use CTRL or drag with mouse to select multiple files in the selection box. </h5>
                    <h5> Maximum file size is <?php echo FILE_SIZE_LIMIT/1000/1000/1000; ?> GB. </h5>
                    <h5> Coming soon... </h5>

                    <form method="post"  enctype="multipart/form-data" action="fileUpload.php" autocomplete="off">
                        <div class="form-group">
                            <label>Multiple Files:</label>
                            <input type="file" name="fileMulti[]" id="fileMulti" multiple required>
                        </div>  
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><span class="col-sm-6 control-label">Who can view?</span></b><br>
                                    <div class="col-sm-6 pull-left">
                                        <input type="radio" id="perm" name="perm" value="<?php echo GROUP_NONE; ?>" checked="checked"> Everyone<br>
                                        <input type="radio" id="perm" name="perm" value="<?php echo GROUP_REGISTERED; ?>"> Registered<br>
                                        <input type="radio" id="perm" name="perm" value="<?php echo GROUP_FRIENDS; ?>"> VIP Friends<br>
                                        <?php //TODO here add way to specify custom group number?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <b><span class="col-sm-6 control-label">User specific:</span></b><br>
                                    <b><span class="col-sm-6 control-label">Coming soon</span></b><br>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="upload-submit"></label><br><br><br>
                            <input id="upload-submit" class="btn btn-default" type="submit" value="Submit Files" style="margin-top:5px;"> 
                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal fade -->



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
