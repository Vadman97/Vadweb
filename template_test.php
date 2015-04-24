<?php
$layout = "base";
ob_start();
?>
<body>
        <h1> WOWOWOW BODY OF TEMPLATE </h1>
</body>
<?php
$content = ob_get_clean();

("base.php");
?>
