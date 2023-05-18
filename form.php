<?php
$name = $_GET["name"];
$radio = $_GET["radio"];
$comment = $_GET["comment"];

$name = htmlentities($name,ENT_QUOTES,"UTF-8");
$radio = htmlentities($radio,ENT_QUOTES,"UTF-8");
$comment = htmlentities($comment,ENT_QUOTES,"UTF-8");

$name

echo <<< _FORM_
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>確認画面</title>
</head>
<body>
    <p>
        ■お名前<br>
        $name
    </p>
    <p>
        ■好きな季節<br>
        $radio
    </p>
    <p>
        ■理由<br>
        $comment
    </p>
</body>
</html>
_FORM_;

?>