<?php
ob_clean();
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("util.php");

function record($name)
{
	$sql = SQLCon::getSQL();
	$sql->sQuery("INSERT INTO Computers (Name) VALUES ('$name')");
}

if ($_SERVER['REQUEST_METHOD'] == "GET")
{
	record($_GET["comp"]);
}

?>
