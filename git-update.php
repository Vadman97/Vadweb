<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		print_r($_POST);
		//echo "<br><br><br>";
		//echo shell_exec("git pull origin master");
		//echo shell_exec("ls -lsah");
		exec("git pull origin master 2>&1", $return);
		print_r($return);
		//echo shell_exec("ls -lsah");
		echo "DONE PULLING";
	}
?>