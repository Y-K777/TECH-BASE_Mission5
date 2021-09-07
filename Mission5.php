
<?php
  // データベース名：tb230323db
  $dsn = 'データベース名';

  // ユーザー名：tb-230323
  $user = 'ユーザー名';

  // パスワード：X5x9JhVULp
  $password = 'パスワード';
  // テーブル
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

  // テーブルの作成
  // $sql = "CREATE TABLE IF NOT EXISTS Mission_5_var3" // 「IF NOT EXISTS」は「もしまだこのテーブルが存在しないなら」という意味。
  // ." ("
  // . "id INT AUTO_INCREMENT PRIMARY KEY," //id:自動で登録されているナンバリング
  // . "name char(32)," //name : 名前を入れる。（文字列、半角英数32文字）
  // . "comment TEXT," //comment:コメントを入れる。（文字列、長い文章も入る。）
  // . "time char(32),"
  // . "password char(32)"
  // .");";
  // $stmt = $pdo->query($sql);

  // SHOW TABLES：データベースのテーブル一覧を表示「Mission4-3」
  // $sql ='SHOW TABLES';
  // $result = $pdo -> query($sql);
  // foreach ($result as $row){
  //     echo $row[0];
  //     echo '<br>';
  // }
  // echo "<hr>";

  // SHOW CREATE TABLE文：作成したテーブルの構成詳細を確認する。「Mission4-4」
  // $sql ='SHOW CREATE TABLE Mission_5_var3';
  // $result = $pdo -> query($sql);
  // foreach ($result as $row){
  //     echo $row[1];
  // }
  // echo "<hr>";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mission_5</title>
</head>
<body>
<!-- 編集番号したい番号送信フォーム -->
  <form action="Mission5.php" method="post">
      <input type="number" name="edit" action="check_error.php" placeholder="編集する番号を入力">
      <input type="number" name="editpass" action="check_error.php" placeholder="パスワード入力" autocomplete="off">
      <input type="submit" name="submit" value="番号送信">
  </form>
  
  <!--編集番号を受け取って編集したいデータを投稿フォームに表示させるためのコード-->
  <?php 
    //Noticeメッセージの表示を消す
    error_reporting(E_ALL & ~E_NOTICE);

    // 編集関係の変数
    $edit = $_POST["edit"];
    // 編集時入力のパスワード
    $editpass = $_POST["editpass"];
    
    if(!empty($edit) && !empty($editpass)){
      $sql = 'SELECT * FROM Mission_5_var3';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row){
        if ($edit == $row['id'] && $editpass == $row['password']) {
          $editnum = $row['id'];
          $editname = $row['name'];
          $editstr = $row['comment'];
        }
      }
    }
  ?>

  <form action="Mission5.php" method="post">
    <!--新規・編集の送信フォーム-->
      <h5 class="h5">
          新規投稿時はパスワード（数字）の記入が必須
      </h5>
      
      <input type="hidden" name="editnumber" value="<?php echo "$editnum" ?>">
      <input type="text" name="name" action="check_error.php"  placeholder="名前を入力" 
        value="<?php echo "$editname"; ?>"><!--名前-->
      <input type="text" name="str" action="check_error.php" placeholder="コメント欄" 
        value="<?php echo "$editstr"; ?>"><!--コメント-->
      <!--password-->
      <input type="number" name="password" action="check_error.php" placeholder="パスワード設定" 
      autocomplete="off">
      <input type="submit" name="submit"><br>
      
      <!--削除のフォーム-->
      <input type="number" name="del" action="check_error.php" placeholder="削除する番号を入力">
      <input type="number" name="delpass" action="check_error.php" placeholder="パスワード入力" autocomplete="off">
      <input type="submit" name="submit" value="削除">
      
      <style>
          .h5 {
              margin: 0px;
          }
      </style>
      <?php
        echo "<hr>";
      ?>
  </form>

  <?php
    //Noticeメッセージの表示を消す
    error_reporting(E_ALL & ~E_NOTICE);
    //名や文章の変数
    $editnum = $_POST["editnumber"];
    $TxStr = $_POST["str"];
    $TxName = $_POST["name"];
    $TxPass = $_POST["password"];
    // 削除したい番号の変数
    $del = $_POST["del"];
    // 削除時入力のパスワード
    $delpass = $_POST["delpass"];

    // 名前とコメントが入力、送信された際にデータの挿入の条件分岐
    if (empty($editnum) && !empty($TxStr) && !empty($TxName) && !empty($TxPass)) {
      $sql = $pdo -> prepare("INSERT INTO Mission_5_var3 (name, comment, time, password) VALUES (:name, :comment, :time, :password)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':time', $time, PDO::PARAM_STR);
      $sql -> bindParam(':password', $password, PDO::PARAM_STR);
      $name = $TxName;//名前
      $comment = $TxStr;//コメント
      $time = date("Y/m/d H:i:s");//日付
      $password = $TxPass;//password.
      $sql -> execute();
    }

    // 編集の条件分岐と動作
    if (!empty($editnum) && !empty($TxStr) && !empty($TxName)) {
      $id = $editnum; //変更する投稿番号
      $name = $TxName;
      $comment = $TxStr; //変更したい名前、変更したいコメントは自分で決めること
      $sql = 'UPDATE Mission_5_var3 SET name=:name,comment=:comment WHERE id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    }

    // 削除の条件分岐と動作
    if (!empty($del) && !empty($delpass)) {
      $sql = 'SELECT * FROM Mission_5_var3';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row){
        if ($del == $row['id'] && $delpass == $row['password']) {
          $id = $del;
          $sql = 'delete from Mission_5_var3 where id=:id';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute();
        }
      }
    }

    // SELECT文：入力したデータレコードを抽出し、表示する「Mission4-6」
    $sql = 'SELECT * FROM Mission_5_var3';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
      //$rowの中にはテーブルのカラム名が入る
      echo $row['id'].',';
      echo $row['name'].',';
      echo $row['comment'].',';
      echo $row['time'].'<br>';
      echo "<hr>";
    }
  ?>
</body>
</html>