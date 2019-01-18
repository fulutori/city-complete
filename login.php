<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>city-complete ログイン</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<style>
body {
	margin-right: auto;
	margin-left: auto;
	width: 90%;
	max-width: 600px;
}
</style>
</head>
<body>
<h2>city-complete</h2>
<hr>
<form method="post">
<div class="form-group">
<input type="text" class="form-control" name="user" placeholder="ユーザー名" required />
</div>
<div class="form-group">
<input type="password" class="form-control" name="pass" placeholder="パスワード" required />
</div>
<button type="submit" class="btn btn-default" name="login">ログイン</button>　
<button type="button" class="btn btn-default" onclick="location.href='index.php'">ログインせずに利用する</button>
<br>
<br>
<a href="register.php">ユーザー登録はこちら</a>
</form>
</body>
</html>
<?php
session_start();
if( isset($_SESSION['user']) != "") {
	header("Location: index.php");
}
include_once 'dbconnect.php';
if(isset($_POST['login'])) {
	$user = htmlspecialchars($_POST['user']);
	$pass = htmlspecialchars($_POST['pass']);
	$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
	$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$sql = "SELECT * FROM users WHERE user=?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($result['user']=="") {
		?><div class="alert alert-danger" role="alert">このユーザーは登録されていません</div><?php 
		exit();
	}
	$db_hashed_pwd = $result['pass'];
	$user_id = $result['user_id'];
	if (password_verify($pass, $db_hashed_pwd)) {
		$_SESSION['user'] = $user_id;
		header("Location: ./index.php");
		exit();
	} else { 
		?><div class="alert alert-danger" role="alert">パスワードが一致しません。</div><?php 
	}
}
?>