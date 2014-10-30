<?php
session_start();
class DB 
{
	public static $host="p:localhost"; // Host name 
	public static $username="vadim"; // Mysql username 
	public static $password="789601234"; // Mysql password 
	public static $db_name="website"; // Database name 
	public static $tbl_name="LoginData"; // Table name 
	public static $port = 3310;
	public static $con;
}
 
DB::$con = mysqli_connect(DB::$host,DB::$username,DB::$password,DB::$db_name,DB::$port);

if (mysqli_connect_errno()) {die("Failed to connect to MySQL: " . mysqli_connect_error());}

// Connect to server and select databse.
//mysql_connect("$host", "$username", "$password")or die("ERROR: Cannot connect to MySQL"); 

//mysql_select_db("$db_name")or die("ERROR: Cannot select MySQL Database");

/*   MAYBE ADMIN SETTING OR SOMETHING, RUN FUNCTION
$sql = "create database if not exists website";
mysql_query($sql) or die("Error creating database.");

$sql="CREATE TABLE if not exists LoginData(
ID INT NOT NULL AUTO_INCREMENT, 
Username CHAR(50) BINARY NOT NULL,
Email CHAR(50) NOT NULL,
Age INT NOT NULL, 
Password CHAR(50) NOT NULL,
IP VARCHAR(255) NOT NULL,
IPwithProxy VARCHAR(255) NOT NULL,
PRIMARY KEY (ID))";
mysql_query($sql) or die("Error creating login table.");

$sql="CREATE TABLE if not exists AdminData(Username CHAR(50) BINARY NOT NULL)";
mysql_query($sql) or die("Error creating admin table.");

$sql="CREATE TABLE if not exists FileUploads(
upload_id BIGINT AUTO_INCREMENT NOT NULL,
user_id INT NOT NULL,
FilePath VARCHAR(255) BINARY NOT NULL,
PRIMARY KEY(upload_id),
FOREIGN KEY(user_id) REFERENCES LoginData(id) ON DELETE CASCADE ON UPDATE CASCADE 
)";
mysql_query($sql) or die("Error creating fileUploads table.");

$sql="CREATE TABLE if not exists Logins(
login_id BIGINT AUTO_INCREMENT NOT NULL,
user_id INT NOT NULL,
IP VARCHAR(255) NOT NULL,
IPwithProxy VARCHAR(255) NOT NULL,
Success TINYINT NOT NULL,
login_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(login_id),
FOREIGN KEY(user_id) REFERENCES LoginData(id) ON DELETE CASCADE ON UPDATE CASCADE 
)";
mysql_query($sql) or die("Error creating Logins table.");

$sql="CREATE TABLE if not exists UserSettings(
setting_id BIGINT AUTO_INCREMENT NOT NULL,
user_id INT NOT NULL,
profile_pic_path VARCHAR(255) NOT NULL,
PRIMARY KEY(setting_id),
FOREIGN KEY(user_id) REFERENCES LoginData(id) ON DELETE CASCADE ON UPDATE CASCADE
)";
mysql_query($sql) or die("Error creating UserSettings table.");

$sql="CREATE TABLE if not exists FileViews(
view_id BIGINT AUTO_INCREMENT NOT NULL,
file_id BIGINT NOT NULL,
user_id INT NOT NULL,
IP VARCHAR(255) NOT NULL,
IPwithProxy VARCHAR(255) NOT NULL,
view_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(view_id),
FOREIGN KEY(file_id) REFERENCES FileUploads(upload_id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY(user_id) REFERENCES LoginData(id) ON DELETE CASCADE ON UPDATE CASCADE
)";
mysql_query($sql) or die("Error creating FileViews table.");
*/
echo '<script src="/javascript/jquery-1.11.0.min.js"></script><script src="/bootstrap/js/bootstrap.min.js"></script>';
echo '<meta name="format-detection" content="telephone=no">';
echo '<meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="robots" content="index,nofollow">
    <meta name="keywords" content="images, funny pictures, image host, image upload, image sharing, image resize">
    <meta name="description" content="Vadweb is home to the webs most popular image and video content, curated in real time by a dedicated community through commenting, voting and sharing.">
    <meta name="copyright" content="Copyright 2014 Vadweb, INC.">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge;">';
?>
