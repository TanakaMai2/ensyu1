<?php
//送信後画面からの戻り先
$toppage = "./form.html";

//入力情報の受け取りと加工
$name = $_POST["name"];
$radio = filter_input(INPUT_POST,"radio");
$comment = $_POST["comment"];

//無効化
$name = htmlentities($name,ENT_QUOTES,"UTF-8");
$comment = htmlentities($comment,ENT_QUOTES,"UTF-8");

//改行処理
$name = str_replace("\r\n","",$name);
$comment = str_replace("\r\n","",$comment);

//入力チェック
if($name == ""){
    error("名前を入力してください");
}
if(is_NULL($radio)){
    error("好きな季節を選択してください");
}
if($comment == ""){
    error("コメントが入力してください");
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
    $data = str_replace("!comment!",$comment,$data);

    //表示
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
    
    //表示
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
            $SQL = "INSERT INTO form(お名前,好きな季節,理由)VALUES(:name,:radio,:comment)";
            $stmt3 = $dbh -> prepare($SQL);
            $stmt3 ->bindParam(":name",$name);
            $stmt3 ->bindParam(":radio",$radio);
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
    
    //文字力の変化の記述
    if($radio == "春"){
        echo "<span style=\"color: #c0029d;\">{$data}</span>";
        
        exit;
    }
    elseif ($radio == "夏"){
        echo "<span style=\"color: #165df4;\">{$data}</span>";
        exit;
    }
    elseif ($radio == "秋"){
        echo "<span style=\"color: #ad2405;\">{$data}</span>";
        exit;
    }
    else{
        echo "<span style=\"color: #6e5e5b;\">{$data}</span>";
        exit;
    }
}

?>