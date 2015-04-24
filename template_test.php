$layout = "test_template";
ob_start();

<body>
        <h1> WOWOWOW BODY OF TEMPLATE </h1>
</body>

$content = ob_get_clean();

require_once($layout . ".php");


