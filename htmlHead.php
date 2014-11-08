<?php
	//TODO Make the OG tags right everywhere
	require_once("util.php");
	if (!termsAgreed())
	{
		//header("Location: http://www.vadweb.us/terms.php");
	}
	if (!emailVerified())
	{
		//header("Location: http://www.vadweb.us/emailVerify.php");
	}
	echo
	'
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <meta name="format-detection" content="telephone=no"/>
	    <meta name="keywords" content="files, images, funny pictures, image host, image upload, image sharing, image resize, file host, file upload, file sharing"/>
	    <meta name="description" content="Vadweb is home to the webs most popular image and video content, curated in real time by a dedicated community through commenting, voting and sharing."/>
	    <meta name="copyright" content="Copyright 2014 Vadweb, SWAG."/>
	    <meta property="og:title" content="Vadweb File Sharing" />
	    <meta property="og:url" content="http://vadweb.us'. $_SERVER["PHP_SELF"] .'?' . $_SERVER["QUERY_STRING"] .'"/>
	    <meta property="og:description" content="Vadweb is home to the webs most popular image and video content, curated in real time by a dedicated community through commenting, voting and sharing." />
	    
	    <meta http-equiv="X-UA-Compatible" content="IE=Edge;"/>
	    
	    <link rel="shortcut icon" href="images/vmg.ico"/>
	    <link href="/resource/bootstrap/css/bootstrap.css" rel="stylesheet"/>

	</head>
	';

	function headForView($sqlFileLookupResult)
	{
		ob_clean();
		echo
		'
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">

		<head>
		    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		    <meta name="format-detection" content="telephone=no"/>
		    <meta name="keywords" content="files, images, funny pictures, image host, image upload, image sharing, image resize, file host, file upload, file sharing"/>
		    <meta name="description" content="Vadweb is home to the webs most popular image and video content, curated in real time by a dedicated community through commenting, voting and sharing."/>
		    <meta name="copyright" content="Copyright 2014 Vadweb, SWAG."/>
		    <meta property="og:title" content="Vadweb: File View - ' . $sqlFileLookupResult[0]["FilePath"] .'" />
		    <meta property="og:url" content="http://vadweb.us'. $_SERVER["PHP_SELF"] .'?' . $_SERVER["QUERY_STRING"] .'"/>
		    <meta property="og:description" content="View file '. $sqlFileLookupResult[0]["FilePath"] .'. Vadweb is home to the webs most popular image and video content." />
		    <meta property="og:image" content="http://vadweb.us/file.php?name='.$sqlFileLookupResult[0]["FilePath"].'" />
		    
		    <meta http-equiv="X-UA-Compatible" content="IE=Edge;"/>
		    
		    <link rel="shortcut icon" href="/images/vmg.ico"/>
		    <link href="/resource/bootstrap/css/bootstrap.css" rel="stylesheet"/>

		</head>
		';
	}

?>
