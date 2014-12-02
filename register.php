<?php
require_once("htmlHead.php");
require_once("util.php");

error_reporting(~E_ALL);

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
  ob_clean();
		//ini_set('display_errors', 'On');
        //error_reporting(E_ALL);
  $username = $_POST['username'];
  $email = $_POST['email'];
  $yob = $_POST['yob'];
  
  $encPassData1 = base64_decode($_POST['password']);
  $encPassData2 = base64_decode($_POST['password2']);
  $iv = base64_decode($_POST['iv']);
  $key = base64_decode($_POST['k']);
  $gresponse = $_POST["g-recaptcha-response"];

    //$unencPass1 = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $key, $encPassData1, MCRYPT_MODE_CBC, $iv ), "\t\0 " );
		//$unencPass2 = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_128, $key, $encPassData2, MCRYPT_MODE_CBC, $iv ), "\t\0 " );
  
		//echo $encPassData1 . "<br>" . $encPassData2;
		//die();
  
		//$pass1 = mcrypt_decrypt("AES_256", $clientSalt, $encPass1, 
		//$pass1 = $encPass1;
		//$pass2 = $encPass2;
  $pass1 = $_POST["password"];
  $pass2 = $_POST["password2"];
  
  if (invalidUsername($username))
   echo "E1";
 else if (invalidEmail($email))
   echo "E2";
 else if (invalidYear($yob))
   echo "E3";
 else if (userExists($username, $email))
   echo "E4";
 else if (empty($username) or empty($email) or empty($yob) or empty($pass1))
   echo "E5";
 else if (strpos($username, " ") !== false)
   echo "E6";
 else if (strlen($username) >= 20)
   echo "E7";
 else if (strlen($email) >= 50)
   echo "E8";
 else if (strlen($pass1) >= 50)
   echo "E9";
 else if (strlen($username) < 3)
   echo "E10";
 else if (strlen($pass1) < 6)
   echo "E11";
 else if ($pass1 != $pass2)
   echo "E12";
 else if (isLoggedIn())
   echo "E13";
 else if (!verifyCaptcha($gresponse))
   echo "E14";
 else
 {
   sleep(1.);
   
    if (writeUser($username, $email, $yob, $pass1))
    {
				//echo "<strong>SUCCESS</strong>";
        login($username, $pass1, false);
        initAccountSettings(false);
        echo "S";
    }
  else
    echo "F";
}
exit();
}
?>
<head>
	<link href="/resource/bootstrap/css/simpleTemp.css" rel="stylesheet">
  <title>Register an account for Vadweb!</title>
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
          <li class="active"><a style="color:#FFF" href="/register.php">Register</a></li>
          <li><a style="color:#FFF" href="/files.php">File Uploads</a></li>
          <li><a style="color:#FFF" href="/account.php">Account Settings</a></li>
        </ul>
        <?php printNavBarForms("register.php"); ?>
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <div class="container">

    <div class="starter-template" >
      <h1>Registration</h1>
      <p class="lead" style="overflow:auto; overflow-style:marquee-block">Register here for a Vadweb account, letting you interact with uploaded files and upload content. </p><br><br><br>

      <?php 
      if (isLoggedIn()) {
         echo "<img src='http://i.imgur.com/SEDnnQ9.jpg'></img>";		
			   echo "<h1 style='font-family: Comic Sans MS; color:red;'>Please log out before trying to register again...</h1>";
     }
     ?>        
     <form id="regForm" name="regForm" class="form-horizontal" method="post" onSubmit="return runReg();" <?php if (isLoggedIn()) echo "hidden"; ?>>
      <fieldset>
        
        <!-- Form Name -->
        <legend>User Registration Form</legend>
        
        <!-- Email input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="email">Email</label>
          <div class="col-md-4">
            <input id="email" name="email" type="email" max="50" placeholder="Email" class="form-control input-md" required>
            <span class="help-block">Enter your email</span>
          </div>
        </div>
        
        
        <!-- YOB input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="yob">Year of Birth</label>  
          <div class="col-md-4">
            <input id="yob" name="yob" type="number" min="1900" max="<?php echo date("Y") - 6; ?>" placeholder="Year of Birth" class="form-control input-md" required>
            <span class="help-block">Enter your real Year of Birth, <b>as you may need to confirm it later</b></span>  
          </div>
        </div>
        
        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="username">Username</label>  
          <div class="col-md-4">
            <input id="username" name="username" type="text" max="20" placeholder="Username" class="form-control input-md" required>
            <span class="help-block">Enter your desired username</span>  
          </div>
        </div>
        
        <!-- Password input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="password">Password</label>
          <div class="col-md-4">
            <input id="password" name="password" type="password" min="8" max="50" placeholder="Password" class="form-control input-md" required>
            <span class="help-block">Enter your desired password, at least 6 characters long.</span>
          </div>
        </div>
        
        <!-- Confirm Password input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="password2">Confirm Password</label>
          <div class="col-md-4">
            <input id="password2" name="password2" type="password" min="8" max="50" placeholder="Password" class="form-control input-md" required>
            <span class="help-block">Enter your desired password again</span>
          </div>
        </div>

        <!-- Captcha-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="password2">Enter the reCAPTCHA Text</label>
          <div class="col-md-4">
            <div class="g-recaptcha" data-sitekey="6LeTUf4SAAAAAJ6U9O9s0W6jcr9wPiJgqW60bwWh"></div>
          </div>
        </div>
        
        <!-- Button -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="submit">Press to Submit</label>
          <div class="col-md-4">
            <button id="submit" name="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
        
      </fieldset>
    </form><br><br><br>
    
    <style type="text/css">#ajax-panel { background: #f5f5f5; border: 1px #ddd solid; padding: 20px; margin: 0 0 5px 0; } .loading { padding: 50px 0; text-align: center; }</style>
    <div id="ajax-panel"></div>


  </div>

</div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/resource/jquery/jquery-2.1.1.min.js"></script>
    <script src="/resource/bootstrap/js/bootstrap.js"></script>
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/aes.js"></script>
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/pbkdf2.js"></script>
    <script src="register.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    

  </body>

  </html>
