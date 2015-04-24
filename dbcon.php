<?php
	sessionStart();
	
	function is_session_started()
	{
	    if ( php_sapi_name() !== 'cli' ) {
	        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
	            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	        } else {
	            return session_id() === '' ? FALSE : TRUE;
	        }
	    }
	    return FALSE;
	}

	function sessionStart()
	{
		//$sessionStuffTimerStart = microtime(true); 
		//ini_set("session.cookie_lifetime","86400"); //10800
		//ini_set('session.use_only_cookies',1);
		//setcookie(session_name(),session_id(),time()+86400);
		//$lifetime = 86400;
		//$cookieParams = session_get_cookie_params();
		//session_set_cookie_params($lifetime, $cookieParams["path"], $cookieParams["domain"], false, true);
		//session_write_close();

		session_name("VadwebID");
		session_start();
		session_regenerate_id();
	    /*$_SESSION["sessionOldID"] = session_id();
	    session_regenerate_id();
	    $_SESSION["sessionID"] = session_id();
	    $sessionStuffTimerEnd = microtime(true); 
	    $_SESSION["sessionConfigTime"] = $sessionStuffTimerEnd - $sessionStuffTimerStart;*/

	        incrementPerfCount("Session Initialized");
		//setcookie(session_name(),session_id(),time()+$lifetime);*/

		/*sec_session_start();

		function sec_session_start() 
		{
		    $session_name = 'sec_session_id';   // Set a custom session name
		    $secure = false;
		    $domain = 'vadweb.us';
		    // This stops JavaScript being able to access the session id.
		    $httponly = true;
		    // Forces sessions to only use cookies.
		    if (ini_set('session.use_only_cookies', 1) === FALSE) {
		        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		        exit();
		    }
		    ini_set("session.cookie_lifetime","10800");
		    // Gets current cookies params.
		    $cookieParams = session_get_cookie_params();
		    session_set_cookie_params($cookieParams["lifetime"] + 10800,
		        $cookieParams["path"], 
		        $domain, 
		        $secure,
		        $httponly);
		    // Sets the session name to the one set above.
		    session_name($session_name);
		    session_start();            // Start the PHP session 
		    session_regenerate_id();    // regenerated the session, delete the old one. 
		}*/
	}

    function incrementPerfCount($name = NULL)
    {
        if ($name == NULL)
            return;
        $sql = SQLCon::getSQL();    
        $count = count($sql->sQuery("select * from PerformanceDebug where Type='$name'")->fetchAll());
        if ($count == 0)
            $sql->sQuery("insert into PerformanceDebug (Type, Value) values ('$name', 0)");
        
        $sql->sQuery("UPDATE PerformanceDebug SET Value=Value+1 WHERE  Type='$name'");
    }

	class SQLCon
	{
		private $user;
		private $pass;
		public $dbc = NULL;
		private static $sqlPointer = NULL;
		private function __construct()
		{
			$this->user = getenv("DB_USER");
			$this->pass = getenv("DB_PASS");
			try 
			{
				$this->dbc = new PDO('mysql:host=localhost;dbname=vadweb;charset=utf8', $this->user, $this->pass);
				$this->dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) 
			{
				print "Error!: " . $e->getMessage() . "<br/>";
				die("MYSQL ERROR");
			}
		}
		public static function getSQL()
		{
			//if (SQLCon::$sqlPointer == NULL)
			{
				SQLCon::$sqlPointer = new SQLCon();
				//apc_store("newSQLPointer", apc_fetch("newSQLPointer") + 1);
			}
			//apc_store("cachedSQLPointer", apc_fetch("cachedSQLPointer") + 1);
			return SQLCon::$sqlPointer;
		}
		function transaction()
		{
			try
			{
				$this->dbc->beginTransaction();
				$arg_list = func_get_args();
				
				foreach ($arg_list as &$a)
				{
					$this->dbc->exec($a);
				}
				$this->dbc->commit();
			}
			catch (Exception $e) 
			{
				$this->dbc->rollBack();
				echo "<br><br><b>MYSQL Error: " . $e->getMessage() . "</b><br><br>";
			}
		}
		function prepStmt($statement)
		{
			try
			{
				return $this->dbc->prepare($statement); //returns a prepared statement
			}
			catch (Exception $e) 
			{
				echo "<br><br><b>MYSQL Error: " . $e->getMessage() . "</b><br><br>";
			}
		}
		function execute($statement)
		{
			try
			{
				if ($statement->execute()) //returns stmt object on success
					return $statement;
				return false;
			}
			catch (Exception $e) 
			{
				echo "<br><br><b>MYSQL Error: " . $e->getMessage() . "</b><br><br>";
			}
		}
		function bindParam($stmt, $placeholder, $value)
		{
			try
			{
				$stmt->bindParam($placeholder, $value);
			}
			catch (Exception $e) 
			{
				echo "<br><br><b>MYSQL Error: " . $e->getMessage() . "</b><br><br>";
			}
		}
		function quote($query)
		{
			try
			{
				return $this->dbc->quote($query);
			}
			catch (Exception $e) 
			{
				echo "<br><br><b>MYSQL Error: " . $e->getMessage() . "</b><br><br>";
			}
		}
		function sQuery($sqlQuery) //simple sql query, should be safe in terms of MYSQL injection
		{
			try
			{
				//$sqlQuery = $this->dbc->quote($sqlQuery);
				$pdostmt = $this->dbc->prepare($sqlQuery);
				$pdostmt->execute();
				return $pdostmt;
			}
			catch (Exception $e) 
			{
				echo "<br><br><b>MYSQL Error: " . $e->getMessage() . "</b><br><br>";
			}
		}
		function configTables()
		{			
		    $Files = "CREATE TABLE IF NOT EXISTS `Files` (
							`File_ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
							`User_ID` BIGINT(20) NOT NULL,
							`FilePath` VARCHAR(128) NOT NULL COLLATE 'utf8mb4_unicode_ci',
							`Permission` VARCHAR(16000) NOT NULL DEFAULT '*' COLLATE 'utf8mb4_unicode_ci',
							`Type` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_unicode_ci',
							`CreatedTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
							`NSFW` BIT(1) NOT NULL DEFAULT b'0',
							PRIMARY KEY (`File_ID`),
							INDEX `FK_Files_UserData` (`User_ID`),
							CONSTRAINT `FK_Files_UserData` FOREIGN KEY (`User_ID`) REFERENCES `UserData` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE
						)
						COLLATE='utf8mb4_unicode_ci'
						ENGINE=InnoDB;";

			$FileViews = "CREATE TABLE IF NOT EXISTS `FileViews` (
								`View_ID` BIGINT(11) NOT NULL AUTO_INCREMENT,
								`File_ID` BIGINT(11) NOT NULL,
								`User_ID` BIGINT(11) NULL,
								`IP` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`IPwithProxy` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`Device` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`ViewSource` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
								`Duration` TIME NULL DEFAULT NULL,
								`Time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
								PRIMARY KEY (`View_ID`),
								FOREIGN KEY (`File_ID`) REFERENCES `Files` (`File_ID`) ON UPDATE CASCADE ON DELETE CASCADE,
								FOREIGN KEY (`User_ID`) REFERENCES `UserData` (`ID`) ON UPDATE CASCADE ON DELETE CASCADE
							)
							COLLATE='utf8mb4_unicode_ci'
							ENGINE=InnoDB;";

			$GeneralViews = "CREATE TABLE IF NOT EXISTS `GeneralViews` (
								`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								`Page` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`IP` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`IPwithProxy` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`Device` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`IsLoggedIn` BIT(1) NOT NULL DEFAULT b'0',
								`Time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
								PRIMARY KEY (`ID`)
							)
							COLLATE='utf8mb4_unicode_ci'
							ENGINE=InnoDB;";


			$LoginAttempts = "CREATE TABLE IF NOT EXISTS `LoginAttempts` (
								`Login_ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
								`Username` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`Password` CHAR(129) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`IP` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`IPwithProxy` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`Device` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
								`Success` BIT(1) NOT NULL DEFAULT b'0',
								`Time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
								PRIMARY KEY (`Login_ID`)
							)
							COLLATE='utf8mb4_unicode_ci'
							ENGINE=InnoDB;";


			$UserData = "CREATE TABLE IF NOT EXISTS `UserData` (
								`ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
								`Username` CHAR(50) NOT NULL,
								`Email` CHAR(50) NOT NULL,
								`YOB` INT(11) UNSIGNED NOT NULL,
								`Password` CHAR(129) NOT NULL,
								`GroupVal` TINYINT(4) NOT NULL DEFAULT '1',
								`IP` VARCHAR(255) NOT NULL,
								`IPwithProxy` VARCHAR(255) NOT NULL,
								PRIMARY KEY (`ID`)
							)
							COLLATE='utf8mb4_general_ci'
							ENGINE=InnoDB;";

			$PerformanceDebug = "CREATE TABLE IF NOT EXISTS `PerformanceDebug` (
  								`Type` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  								`Value` bigint(20) NOT NULL
							) 
							ENGINE=InnoDB 
							COLLATE=utf8mb4_unicode_ci;";

			$this->transaction($PerformanceDebug, $UserData, $LoginAttempts, $GeneralViews, $Files, $FileViews, $PerformanceDebug);
        }
	}

?>
