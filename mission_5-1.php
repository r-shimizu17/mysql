<!DOCTYPE html>
<html>
<head>
　　<meta charset="UTF-8">
    <link rel="stylesheet" href="sample.css">
</head>
<body>
     <form action="" method="post">
       投稿フォーム<br>
        名前：    <input type="text" name="namae" value="<?php echo $edit_name; ?>"><br>
        コメント：<input type="text" name="comment" value="<?php echo $edit_comment; ?>"><br>
        パスワード：<input type="passward" name="passward"  value="<?php echo $edit_pass; ?>"> <br>        
                  <input type="submit" name="submit"><br><br>
                  <input type="hidden" name="edit_post" value="<?php echo $edit_number; ?>">
          
        投稿番号：<input type="text" name="sakujonum"><br>
        パスワード：<input type="passward" name="sakujopass"> <br>
                  <input type="submit" name="sakujo" value="削除"><br><br>

        投稿番号：<input type="text" name="hennshuunum"><br>
        パスワード：<input type="passward" name="hennshuupass"><br>
                   <input type="submit" name="hennshuu" value="編集"><br><br><hr>
     </form>

<?php

 // DB接続設定
 $dsn = 'データベース名';
 $user = '名前';
 $password = 'パスワード';
 $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

 //テーブル作成
 $sql = "CREATE TABLE IF NOT EXISTS tbtest"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "name char(32),"
  . "comment TEXT"
  .");";
 $stmt = $pdo->query($sql);

 $name = $_POST['namae'];
 $comment = $_POST['comment'];
 $date = date("Y/m/d H:i:s");
 $password = $_POST['password'];
 ?> 

<?php
//新規投稿処理
if(!empty($comment) && !empty($name) && !empty($password)){
    //INSERT文を使ってデータ（レコード）の登録
	$sql = $pdo -> prepare("INSERT INTO tbtest (name, comment,date,password) VALUES (:name, :comment, :date, :password)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
	$sql -> execute();
}
?>
</div>

<div>
<?php

//編集番号とパスワードがある場合の処理
$edit = $_POST['hennshuu'];
if(!empty($edit)){
   $id = $edit;
   $memos = $pdo->prepare('SELECT * FROM tbtest WHERE id=?');
   $memos->execute(array($id));
   $memo = $memos->fetch();
   //パスワードが一致していたら行う処理
   if($memo['password'] == $_POST['hennshuupass']){
      $edit_comment = $memo['comment'];
      $edit_number = $id;
      echo "コメントを編集してください"."<br>"."<hr>";
   }
   else{
       echo "編集する番号もしくはパスワードが間違っています"."<br>"."<hr>";
   }
}
?>
</div>

<div>
<?php

//編集番号がhidden属性のフォームにあったときに編集処理を行う
if(!empty($_POST['comment']) && !empty($_POST['hennshuupass'])){
    //入力されているデータレコードの内容を編集
	$id = $_POST['edit_post']; //変更する投稿番号
	$comment = $_POST['comment']; 
	$prepare = $pdo->prepare('UPDATE tbtest SET comment=:comment WHERE id=:id');
	$prepare->bindParam(':comment', $comment, PDO::PARAM_STR);
	$prepare->bindParam(':id', $id, PDO::PARAM_INT);
	$prepare->execute();
	echo "編集しました!!"."<br>"."<hr>";
}
?>
</div>

<div>
<?php

//削除機能
if(!empty($_POST['sakujonum'])){
    //入力したデータレコードを削除する
	$id = $_POST['sakujonum'];
	$memos = $pdo->prepare('SELECT * FROM tbtest WHERE id=?');
   $memos->execute(array($id));
   $memo = $memos->fetch();
   
   if($memo['password'] == $_POST['sakujopass']){
      $prepare = $pdo->prepare('delete from tbtest where id=:id');
	$prepare->bindParam(':id', $id, PDO::PARAM_INT);
	$prepare->execute();
	echo "削除しました！！"."<br>"."<hr>";
   }
   else{
       echo "削除する番号もしくはパスワードが間違っています"."<br>"."<hr>";
   }
	
}
?>
</div>

<?php
//データレコードを抽出し、表示する
	$serect = $pdo->query('SELECT * FROM tbtest');
	$results = $serect->fetchAll();
?>
	<div>
	    <?php  
	    foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].',';
	echo $row['password'].'<br>';
	echo "<hr>";
	}
?>
</body>
</html>
