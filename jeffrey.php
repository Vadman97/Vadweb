<html>
hi
<?php

	echo file_get_contents("http://fasttimes.me/meets/15-04-29/");
	$content = file("http://fasttimes.me/meets/15-04-29/");
	print_r($content);
	echo "hi";
?>
</html>
