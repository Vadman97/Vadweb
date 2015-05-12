<?php

require_once("util.php");

?>

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
        
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <?php
                    if (!isLoggedIn()) {
                ?>
                <li><a style="color:#FFF" href="/register.php">Register</a></li>
                <?php
                    }
                ?>
                <li><a style="color:#FFF" href="/files.php">File Uploads</a></li>
                <li><a style="color:#FFF" href="/account.php">Account Settings</a></li>
            </ul>
            
            <?php printNavBarForms("files.php"); ?>
        </div>
    </div><!--/.navbar-collapse -->
</div>


