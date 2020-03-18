
<?php

//4-1接続---------------------------------------------------------------------------------------------------

	$dsn = "データベース名";
	$user = "ユーザー名";
	$password = "パスワード";
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//4-2テーブル作成-----------------------------------------------------------------------------------------
	$sql = "CREATE TABLE IF NOT EXISTS tbte"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "created DATETIME"
	.");";
	$stmt = $pdo->query($sql);


// ファイルに書き込み 定義（編集のフォームに表示させるため ）---------------------------------------------------------------------------
	$sql = 'SELECT * FROM tbte';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		$num= $row['id'];
		$come=$row['comment'];
		$nam= $row['name'];
		$time= $row["created"];

//ファイルに書き込み-------------------------------------------------------------------
 $newData=$num."<>".$come."<>".$nam."<>".$time."\n";	
$dataFile ="mission5.txt";
$fp= fopen($dataFile,'a');
    fwrite($fp, $newData);
    fclose($fp);
}

//4編集表示--------------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_POST["hensyu_No"])&&$_POST['hensyu_pass']==="pass"&&file_exists("mission5.txt")){

$henshu_1   = $_POST["hensyu_No"];
$henshu_file = file("mission5.txt");
for ($k = 0; $k < count($henshu_file) ; $k++){ 
$hensyu_Data = explode("<>", $henshu_file[$k]);  //k番目に入力されたデータを<>で分割する

if($hensyu_Data[0]==$henshu_1){

$hensyu_name = $hensyu_Data[2];
$hensyu_comment=$hensyu_Data[1];
$hensyu_No=$hensyu_Data[0];

}}}
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
?>

<!DOCTYPE html>
<html lang="ja">
<head>
     <meta charset="utf-8">
     <title>簡易掲示板</title>
</head>
<body>
<p>パスワードはpassです。 </p>
<p>名前とコメントが空欄でも動くので動かしてみてください。 </p>
<p>よろしくお願いします。 </p><br/>

<p>【投稿フォーム】 </p>
    	<form action="mission_5-2.php" method="POST">
	<input type="text" name="message" placeholder="コメント" value="<?php if (!empty($_POST["hensyu_No"])&&is_numeric($_POST["hensyu_No"])) {echo $hensyu_comment;} ?>"><br/>
	<input type="text" name="user"  placeholder="名前"  value="<?php if (!empty($_POST["hensyu_No"])&&is_numeric($_POST["hensyu_No"])) {echo $hensyu_name ;}?>"><br/>
	<input type="password" name="toukou_pass"  placeholder="パスワード"  >
	<input type="hidden" name="hensyuNo_hyouzi" value="<?php if(!empty($_POST["hensyu_No"])) {echo $_POST["hensyu_No"]; }?>">

	<input type="submit" name="toukou" value="送信"></br>

<?php
if(isset($_POST['toukou'])&&empty($_POST["hensyuNo_hyouzi"])&&$_POST['toukou_pass']!="pass"&&!empty($_POST['toukou_pass'])){
	echo "パスワードが違います。";
}
if(isset($_POST['toukou'])&&empty($_POST["hensyuNo_hyouzi"])&&empty($_POST['toukou_pass'])){
	echo "パスワードを入力してください。";


}if(isset($_POST['toukou'])&&!empty($_POST["hensyuNo_hyouzi"])&&$_POST['toukou_pass']!="pass"&&!empty($_POST['toukou_pass'])){
	echo "パスワードが違います。";
}
if(isset($_POST['toukou'])&&!empty($_POST["hensyuNo_hyouzi"])&&empty($_POST['toukou_pass'])){
	echo "パスワードを入力してください。";
}?></br>

	</form>


<p>【削除フォーム】 </p>
	<form action="mission_5-2.php" method="POST">
	<input type="text" name="deleteNo" placeholder="削除番号"><br/>
	<input type="password" name="del_pass"  placeholder="パスワード"  >
        <input type="submit" name="delete" value="削除"><br/>
<?php
if (isset($_POST["delete"])&&$_POST['del_pass']!="pass"&&!empty($_POST['del_pass'])){
	echo "パスワードが違います。";
}
if(isset($_POST["delete"])&&empty($_POST['del_pass'])){
	echo "パスワードを入力してください。";

}?><br/>
   
 	</form>


<p>【編集フォーム】 </p>
     <form action="mission_5-2.php" method="POST">
	<input type="text" name="hensyu_No" placeholder="編集番号"><br/>
	<input type="password" name="hensyu_pass"  placeholder="パスワード"  >
	<input type="submit" name="hensyu" value="編集"><br/>
	
<?php

if(isset($_POST["hensyu"])&&is_int($_POST["hensyu_No"])&&$_POST['hensyu_pass']==="pass"){
	echo "数字を入力してください。";
}
if (isset($_POST["hensyu"])&&$_POST['hensyu_pass']!="pass"&&!empty($_POST['hensyu_pass'])) {
	echo "パスワードが違います。";
}

if(isset($_POST["hensyu"])&&empty($_POST['hensyu_pass'])){
	echo "パスワードを入力してください。";
}?>
         
	</form>


<?php
// 新規投稿----------------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_POST["toukou"])&&$_POST["toukou_pass"]==="pass"&&empty($_POST["hensyuNo_hyouzi"])&&file_exists("mission5.txt")){



$sql = $pdo->prepare("INSERT INTO tbte(name, comment,created) VALUES (:name, :comment, :created)");

	$name = $_POST["user"]; 	
	$sql -> bindParam(":name", $name, PDO::PARAM_STR);
	$comment = $_POST["message"];
	$sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
	$sql -> bindParam(":created", $created, PDO::PARAM_STR);
	$created = date('Y-m-d H:i:s');
	$sql->execute();

}


//編集機能-------------------------------------

if(isset($_POST["hensyuNo_hyouzi"])&&$_POST['toukou_pass']==="pass"&&isset($_POST["toukou"])&&file_exists("mission5.txt")){


	$id =$_POST["hensyuNo_hyouzi"] ; //変更する投稿番号
	$name = $_POST["user"];
	$comment = $_POST["message"]; //変更したい名前、変更したいコメントは自分で決めること
	$sql ='update tbte set name=:name,comment=:comment,created=:created where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);	
	$stmt -> bindParam(':created', $created, PDO::PARAM_STR);
	$created = date('Y-m-d H:i:s');
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
}

//4-8 削除機能-----------------------------------------------------------------------------------------------------------------------------------------------------

if (isset($_POST["delete"])&&$_POST["del_pass"]==="pass") {

	$id = $_POST["deleteNo"];	
	$sql = 'delete from tbte where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
}

//4テーブルに表示--------------------------------
	$sql = 'SELECT * FROM tbte';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['comment'].',';
		echo $row['name'].',';
		echo $row["created"]."<br>";
		echo "<hr>";
}
?>

