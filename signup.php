<!-- //新規登録画面 -->

<?php

require_once('config.php');
require_once('function.php');

session_start();
//以下の'POST'は75行目の新規登録が押されたら､実行されるという意味
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $password = $_POST['password'];
//バリデーションの判定の変数として
    $errors = array();

    // バリデーション echo使わなくても以下のような書き方が可能
    if ($name == '')
    {
        //未入力なら67行目にユーザネームが未入力ですと表示するPHPを挿入
        $errors['name'] = 'ユーザネームが未入力です';
    }

    if ($password == '')
    {
        //未入力なら74行目にパスワードが未入力ですと表示するPHPを挿入
        $errors['password'] = 'パスワードが未入力です';
    }


// バリデーション突破後
    if (empty($errors))
    {
        $dbh = connectDatabase();

        $sql = "insert into users (name, password, created_at) values
                (:name, :password, now());";
                //なぜこの時点で$nameではなく:nameにする??→発行するsql文に$を入れると認識しない。なのでbindParamで:の内容を入力する。
        $stmt = $dbh->prepare($sql);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":password", $password);

        $stmt->execute();

        var_dump($_POST);
        echo '<hr>';
        var_dump($errors);
//insert into でデータを登録するだけでなくlogin.phpにも飛ぶようにする
        header('Location: login.php');
        exit;

    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録画面</title>
</head>
<body>
    <h1>新規登録画面です！</h1>
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
      <input type="submit" value = "新規登録">
    </form>
    <a href="login.php">ログインはこちら</a>
</body>
</html>