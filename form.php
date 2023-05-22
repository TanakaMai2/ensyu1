<?php
$toppage = "./form.html";


$name = $_POST["name"];
$radio = $_POST["radio"];
$comment = $_POST["comment"];

$name = htmlentities($name,ENT_QUOTES,"UTF-8");
$radio = htmlentities($radio,ENT_QUOTES,"UTF-8");
$comment = htmlentities($comment,ENT_QUOTES,"UTF-8");

$name = str_replace("\r\n","",$name);
$radio = str_replace("\r\n","",$radio);
$comment = str_replace("\r\n","",$comment);

//入力チェック
if($name == ""){
    error("名前が未入力です");
}
if($comment == ""){
    error("コメントが未入力です");
}

//分岐チェック
if($_POST["mode"] == "post"){
    conf_form();
}
elseif($_POST["mode"] == "send"){
    send_form();
}

//確認画面
function conf_form(){
    global $name;
    global $radio;
    global $comment;

    //テンプレート読み込み
    $conf = fopen("tmpl/conf.tmpl","r") or die;
    $size = filesize("tmpl/conf.tmpl");
    $data = fread($conf , $size);
    fclose($conf);

    //文字置き換え
    $data = str_replace("!name!",$name,$data);
    $data = str_replace("!radio!",$radio,$data);
    $data = str_replace("!comment",$comment,$data);

    echo $data;
    exit;
}
//エラー画面
function error($msg){
    //テンプレート読み込み
    $error = fopen("tmpl/error.tmpl","r");
    $size = filesize("tmpl/error.tmpl");
    $data = fread($error,$size);
    fclose($error);
    //文字置き換え
    $data = str_replace("!errorm!", $msg, $data);
    
    echo $data;
    exit;
}
//データベースに飛ぶ

function send_form(){
        global $name;
        global $radio;
        global $comment;    
    try{
        $dsn = 'mysql:host=localhost; dbname=ensyu; charset=utf8';
        $user = 'testuser';
        $pass = 'testpass';
        $dbh = new PDO($dsn, $user, $pass);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($dbh == null){

        }
        else{
            $SQL = "INSERT INTO form(お名前)VALUES(:name)";
            $stmt1 = $dbh -> prepare($SQL);
            $stmt1 ->bindParam(":name",$name);
            $stmt1 ->execute();

            $SQL = "INSERT INTO form(好きな季節)VALUES(:radio)";
            $stmt2 = $dbh -> prepare($SQL);
            $stmt2 ->bindParam(":radio",$radio);
            $stmt2 ->execute();

            $SQL = "INSERT INTO form(理由)VALUES(:comment)";
            $stmt3 = $dbh -> prepare($SQL);
            $stmt3 ->bindParam(":comment",$comment);
            $stmt3 ->execute();
        }
    }
    catch(PDOExeception $e){
        echo "接続失敗";
        echo "エラー内容".$e->getMEssage();
        die();
    }
    //送信完了画面
    $conf = fopen("tmpl/send.tmpl","r") or die;
    $size = filesize("tmpl/send.tmpl");
    $data = fread($conf , $size);
    fclose($conf);

    global $toppage;
    $data = str_replace("!mainp!",$toppage, $data);

    echo $data;
    exit;
}



?>