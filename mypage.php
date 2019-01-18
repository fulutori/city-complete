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
<title>city-complete マイページ</title>
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
	max-width: 350px;
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
<h2>マイページ</h2>
<hr>
<table>
<?php
$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$sql = 'SELECT * FROM users WHERE user_id=?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$user = $result['user'];
$score = $result['score'];
$score_pref = $result['score_pref'];
$score_city = $result['score_city'];
$score_town = $result['score_town'];
echo "<tr><td>ユーザー名</td><td>".$user."</td></tr>\n<tr><td>score</td><td>".$score."</td></tr>\n<tr><td>都道府県*</td><td>".$score_pref."</td></tr>\n<tr><td>市区町村*</td><td>".$score_city."</td></tr>\n<tr><td>町域*</td><td>".$score_town."</td></tr>\n";
?>
</table>
<p>*全ユーザーの中で一番最初に訪れた人にだけ加算されます。</p>
<br>
<a href="index.php">戻る</a>
<br>
<br>
</body>
</html>