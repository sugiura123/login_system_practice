<!-- 最初の画面 ログイン画面なので誰でも閲覧可能 -->
<!-- <DB→nowall  table→users> -->

<?php
require_once('config.php');
require_once('function.php');

session_start();
//何かしら$_SESSIONに入力すると値が入ってしまうので、index.phpに飛んでしまう
//仮に$_SESSION['id'] = 'dede';を書くとindex.phpに飛んでしまう。入力はname,passwordに限定させる。
//$_SESSION['id'] = 'dede';

if (!empty($_SESSION['id']))
{
    header('Location: index.php');
    exit;
}
//100行目でsubmitされた値がpostされたら
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $password = $_POST['password'];

    $errors = array();

    // バリデーション
    if ($name == '')
    {
        $errors['name'] = 'ユーザネームが未入力です';
    }

    if ($password == '')
    {
        $errors['password'] = 'パスワードが未入力です';
    }

    // バリデーション突破後
    if (empty($errors))
    {
        $dbh = connectDatabase();
// :name→入力された名前のこと
        $sql = "select * from users where name = :name and password = :password";
        $stmt = $dbh->prepare($sql);
//値をbindする
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
//fetchは該当するレコードが合った場合、1行分取ってきてくれるということ。それを$rowという配列に格納する
        $row = $stmt->fetch();

        var_dump($row);
        //echo '<hr>';
        //var_dump($errors);
      //  header('Location: login.php');
      //  exit;

//$rowで何か結果が入っていたとする
         if ($row)
        {
//[超重要]$_SESSION['id'] に $row['id']という値を持たせてindex.phpに飛ばす
            $_SESSION['id'] = $row['id'];
            header('Location: index.php');
            exit;
        }
        else
        {
            echo 'ユーザネームかパスワードが間違っています';
        }



    }
}




?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン画面</title>
</head>
<body>
    <h1>ログイン画面です！</h1>
    <form action = "" method="post">
      ユーザーネーム:<input type="text" name="name"><br>
        <?php if ($errors['name']) : ?>
            <?php echo h($errors['name']) ?>
        <?php endif; ?>
        <br>
        パスワード: <input type="text" name="password">
        <?php if ($errors['password']) : ?>
            <?php echo h($errors['password']) ?>
        <?php endif; ?>
        <br>
      <input type="submit" value = "ログイン">
    </form>
    <a href="signup.php">新規ユーザー登録はこちら</a>
</body>
</html>