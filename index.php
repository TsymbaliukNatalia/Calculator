<?php
include "functions.php";

define("PI", 3.14);

$error_flag = false;
$value = "";
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["expression"])){
    $expression = $_POST["expression"];
    $value = $expression;
    if(validateExpression($expression)){
        $result = getGeneralResult($expression);
    } 
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Calculator</title>
</head>

<body>
    <form action="index.php" method="post">
        <input type="text" name="expression" value="<?=$value?>">
        <input type="submit" name="submit" value="Порахувати">
    </form>
    <?php if ($error_flag) { ?>
        <p><?= $error_masage ?></p>
    <?php } else { ?>
        <p>Результат розрахунку = <?= $result ?></p>
    <?php } ?>
</body>

</html>