<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Solitaire</title>
<script src="/javascript/jquery-1.11.0.min.js"></script>
</head>

<body>
<?php 
//require_once '../../lock.php';
//writeComplexView(805);
ob_clean();
header("Location: Solitaire.java"); 
exit();
?>
<object type="application/x-java-applet;version=1.5" width="1280" height="720">
	 <param name="codebase" value="http://vadweb.us/javaApplet/Solitaire/">
     <param name="code" value="Solitaire.class">
     <param name="cache_option" value="no">
</object>
</body>
</html>