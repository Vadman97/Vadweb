<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		print_r($_POST);
		//echo "<br><br><br>";
		echo shell_exec("git pull origin master");
		//echo shell_exec("ls -lsah");
		echo "DONE PULLING";
	}
?>