<!-- 登録した人のみ閲覧可能 -->
<?php
require_once('config.php');
require_once('function.php');

session_start();

//セッション解除
//$_SESSION = array();

if (empty($_SESSION['id']))
{
    header('Location: login.php');
    exit;
}

        $dbh = connectDatabase();

        $sql = "select * from users where id = :id";
        $stmt = $dbh->prepare($sql);
        //:idに $_SESSION['id']を投入する
        $stmt->bindParam(":id", $_SESSION['id']);
        //$stmt->bindParam(":password", $password);
        $stmt->execute();
        $row = $stmt->fetch();
        var_dump($row);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員限定画面</title>
</head>
<body>
    <h1>登録したユーザーのみ閲覧可能です！</h1>
<!-- $rowの中にはid,name,passwordなどが含まれるので、nameだけを表示させたい時は$row['name']とする -->
    <p><?php echo h($row['name']) ?>さんとしてログインしています</p>
    <a href="logout.php">ログアウト</a>
</body>
</html>