<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$characters = 'abcdefghijklmnopqrstuvwxyz';//ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$randStr = "";
for ($i = 0; $i < 1000; $i++)
{
	$randStr .= $characters[rand(0, 25)];
}
echo $randStr;
?>
