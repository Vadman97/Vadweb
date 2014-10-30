<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Website supporting file uploads. Register to view and upload any files.">
<link rel="shortcut icon" href="images/vmg.ico"/>
<link rel="stylesheet" type="text/css" href="css/style1.css">

<title>Vadmans's New Website</title>  
<?php
//OVERALL
	//CHANGE To bootstrap
	//CHANGE age to year of birth
	//FIX SESSION START ERRORS
	//ADD account deletion; account settings page
	//ADD NUM USERS CURRENTLY LOGGED IN
	//ADD IP BANS
	//ADD USER DELETING
	//http://www.jcryption.org/
//UPLOAD PROJECT
	//ADD CUSTOM UPLOADS, TO SUB DIRECTORY (MAYBE PER USER OR SOMETHING)
	//ADD ONLY SEE ONE USERS FILES
	//ADD Searching
	//ADD Add limit on uploads per minute
		//ADD Upload limit for nonadmin users. 
	//REDO FILE LISTING THROUGH AJAX GET REQUESTS AFTER PAGE LOAD
	//Treat java applet uploads, display them prettily

require_once 'lock.php';
require_once 'files/util.php';
$validLoginTemp = validLogin();
?>

<script src="javascript/jsEx1.js"></script>

</head>

<body>

<div class="container">
  <div class="sidebar1">
    <ul class="nav">
      <li><a href="/">VadWeb</a></li>
      <?php  
		//if ($validLoginTemp) 
		echo '<li><a href="/files/upload.php">File upload</a></li>';
		if ($validLoginTemp) 
		echo '<li><a href="accountSettings.php">Account Settings</a></li>';
		
		//$siteTest = "http://www.vadweb.us:8888/home";
		//$siteTest = "http://69.181.144.232/";
		//if (urlExists($siteTest))  //doesnt work maybe do JS pinging
		//{
		//	echo '<li><a href="' . $siteTest . '" target="_blank">Telegraph is online</a></li>';
		//} 
		//else
		//{echo '<li><a href="#" target="_blank">Telegraph is OFFLINE</a></li>';} 
	  ?>
      <li><a href="logout.php"><strong>Logout</strong></a></li>
      <!---<li><a href="#">Link four</a></li>-->
    </ul>
  </div>
    <div class="content">
    <h1>Welcome to VadWeb!</h1>
    <p>
	<?php 
		if ($validLoginTemp) {echo "Welcome user: " . getCurrentUserName();} 
	?>
    <!---</p>
    <p>Input two numbers in the box and press the button.</p>
    <p><input id="ex1Text" type="text" /> </p>
    <p><input id="ex1Text2" type="text" />
    <button type="button" onclick="test1()">Submit</button></p>
    <p id="response1"> </p>
    <br />-->
    
    <!--<p><button type="button" onclick="browserInfo()">Click me for browser info</button></p>
    <p id = "browserResp"> </p>-->
    
    <!---<p><button type="button" onclick="randomLoop()">Click me for randomizer!</button></p>
    <p id = "rLoop"> </p>-->
    <p>
    <?php
	if (isAdmin())
	{
		//add letting admins add more admins.
		//in upload, add tracking of uploads to see who uploaded what
		$sql="SELECT * FROM LoginData ORDER BY ID DESC";
		$result=DB::$con->query($sql);
		$count=mysqli_num_rows($result);
		echo "<strong>Welcome admin!</strong><br>";
		echo "<strong>There are: " . $count . " users registered.</strong><br>";
		$cookieParams = session_get_cookie_params();
		$cookieLife = $cookieParams['lifetime'];
		echo "Session cookie lifetime: "; //maybe change lifetime?		
		if ($cookieLife == 0)
		{
			echo "Expires on browser close, set to 0.<br>";
		}
		if(isSU())
		{
			//$sqlAdmins="SELECT * FROM AdminData";
			$sqlAdmins="SELECT Username FROM LoginData
			WHERE Username NOT IN (SELECT Username FROM AdminData)";
			$resultAdmins=DB::$con->query($sqlAdmins);
			$countAdmins=mysqli_num_rows($resultAdmins);
			
			echo '<br>Add user to admins:<br>
			<form method="post"  enctype="multipart/form-data" action="addAdmin.php"><p>
			
			<label for="addAdminUser">User to add:</label>
			<select name="addAdminUser">';
			
			for ($i = 0; $i < $countAdmins; $i++)
			{
				$row2=mysqli_fetch_array($resultAdmins);
				echo '<option value="'. $row2[0] .'">'. $row2[0] . '</option>';
			}
			
			echo '</select>
			
			<input type="submit" name="submit" value="Submit"></p>
			</form>';
			echo '<div>
			<p>Registered people</p>
			<table border="1">
			<tr>
			<td>Username</td>
			<td>ID</td>
			<td>Age</td>
			<td>Regular IP</td>
			<td>Last successful login</td>
			</tr>
			';
			
			for ($i = 0; $i < $count; $i++)
			{			
			$row=mysqli_fetch_array($result);
			
			$getDate = "
			Select login_date from Logins 
			where user_id='$row[0]' and Success='1'
			order by login_date desc";
			$resultDate=DB::$con->query($getDate);
			$rowDate=mysqli_fetch_array($resultDate);
			
			echo '
			<tr>
			<td>'.$row[1].'</td>
			<td>'.$row[0].'</td>
			<td>'.$row[3].'</td>
			<td>'.$row[5].'</td>
			<td>'.$rowDate[0].'</td>
			
			</tr>
			';
			}
			echo "</table></div>";
		}
		
	}
	else if($validLoginTemp)
	{
		echo "<strong>Welcome regular user!</strong><br>";
		echo "Thanks for checking out the website!<br>";
		echo "Check out the file uploads! Coming soon to ALL users, not just admins.<br>";
		echo "Consider donating to help support the website and so I can by a domain.!<br>";
	}
	?>
    </p>
    <br />
    <div id = "login" style="margin-left:15px;" <?php if ($validLoginTemp) echo 'hidden="hidden"'; ?>>
    	Login here:<br /><br />
        <form name="login" action="checklogin.php" method="post" onsubmit="return validateLogin()">
            Username: <br><input id="myusername" name="myusername" required="required" spellcheck="false" placeholder="username"><br>
            Password: <br><input id="mypassword" name="mypassword" type="password" required="required" placeholder="password"  autocomplete="on" spellcheck="false"><br><br>
        <input type="submit" name="Submit" value="Login"></form>
    </div>
    
    <p id = "reg"></p>
    <p><a href="javaApplet/Solitaire/solitaire.php">Click here</a> to play a free game of Solitaire! (PC ONLY)<br />
	<a href="javaApplet/Solitaire/solitaireSrc.php">Click here</a> to view the Solitaire source code.</p>
    <p><a href="javaApplet/Snake/snake.php">Click here</a> to play a free game of Snake! (PC ONLY)<br />
	<a href="javaApplet/Snake/snakeSrc.php">Click here</a> to view the Snake source code.</p>
    
  <?php  
  	if (!$validLoginTemp)//displays field for registering
	{
		//onclick='displayRegistration();
		echo "<p><a id='regclick' href='regForm.php'>Don't have an account? Click here</a></p>";
		//echo "<script>displayLogin();</script>";
	}
	?>

  </div>
  <div class="sidebar2">
    <h4>Description</h4> 
    <p>VadWeb is a file sharing image board, allowing users to share files, pictures, and other data with one another, as well as to interact through a comment and like system. Originally started by Vadman as a project to learn web-design, it has turned into a secure, anonymous, reliable file sharing and interaction service.</p>
    <h4>Changelog</h4>
    <ol>
      <p class="changelog">ADDITIONS: </p>
      <ol>
    	<li class="changelog">Completely public file uploads. </li>
        <li class="changelog">UI and complete frontend redesign coming soon.</li>
      </ol>
      <p>&nbsp;</p>
      <ol>
        <ol>
          <p class="changelog">FIXES: </p>
          <ol>
            <li class="changelog">Complete redesign of viewing, view count backend.</li>
          </ol>
        </ol>
      </ol>
    </ol>
    <h4>Credits and Aknowledgements</h4> 
    <p>Thanks you Andrew F. for the inspiration. I would be nothing without him. He is my hero and and all around greatguy.
</p>
   <h3 style="text-align: center">Support this project!</h3>
    <p>	<!-- end .content -->
    Please donate to help me keep this project running! Hosting is expensive and requires a lot of electricity/hard drive space, so please help me!<form style="vertical-align:middle;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
    ">
    <input 
    type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    <span id="cdSiteSeal2">
    <button type="button" onclick="showGDSSC()">Show Site Certification</button>
    </p></span>
  
  </div>
</div>
 <footer>
      </span>
    </footer>
</body>
 
</html>
