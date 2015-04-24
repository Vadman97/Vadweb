<?php
	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	$layout = "base";
	ob_start();
?>

<body>
        <h1> WOWOWOW BODY OF TEMPLATE </h1>
</body>

<?php
	$content = ob_get_clean();
	require("base.php");
?>
