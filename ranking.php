<?php
session_start();
include_once 'dbconnect.php';
if(!isset($_SESSION['user'])) {
	header("Location: login.php");
}
$user_id = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>city-complete ランキング</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<style>
body {
	margin-right: auto;
	margin-left: auto;
	width: 90%;
	max-width: 600px;
}
table {
	border-collapse: collapse;
	width: 100%;
	max-width: 400px;
}
table th {
	padding: 10px;
	border: solid 1px black;
}
table td {
	padding: 3px 10px;
	border: solid 1px black;
	text-align: right;
}
</style>
</head>
<body>
<h2>ランキング</h2>
<hr>
<table>
<tr><th>rank</th><th>name</th><th>score</th></tr>
<?php
$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$sql = 'SELECT user_id,user,score FROM users ORDER BY score DESC';
$stmt = $pdo->query($sql);
$rank=1;
foreach ($stmt as $row) {
	$id = $row['user_id'];
	$user = $row['user'];
	$score = $row['score'];
	if ($rank <= 100) {
		if ($id == $user_id) {
			echo "<tr style=\"background: #ffcccc\"><td>".$rank."</td><td>".$user."</td><td>".$score."</td>\n";
		} else {
			echo "<tr><td>".$rank."</td><td>".$user."</td><td>".$score."</td>\n";
		}
	} else {
		if ($id == $user_id) {
			echo "<tr><td colspan=\"3\"></td></tr>";
			echo "<tr style=\"background: #ffcccc\"><td>".$rank."</td><td>".$user."</td><td>".$score."</td>\n";
			echo "<tr><td colspan=\"3\"></td></tr>";
		}
	}
	$rank++;
}
?>
</table>
<br>
<p>※score計算方法</p>
<p>都道府県: +20<br>市区町村: +10<br>町域: +1</p>
<p>全ユーザーの中で一番最初に訪れた人にscoreが加算されます。</p>
<br>
<a href="index.php">戻る</a>
</body>
</html>