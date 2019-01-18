<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>city-complete 登録</title>
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
<h2>ユーザー登録</h2>
<hr>
<div class="form-group">
<form method="post">
<input type="text" class="form-control" name="user" maxlength="30" placeholder="ユーザー名(30字まで)" required />
</div>
<div class="form-group">
<input type="password" class="form-control" name="pass" maxlength="256" placeholder="パスワード(半角英数字記号256文字まで)" required />
</div>
<button type="submit" class="btn btn-default" name="signup">登録</button>
<br>
<br>
<a href="login.php">ログインはこちら</a>
<?php
session_start();
if( isset($_SESSION['user']) != "") {
	header("Location: index.php");
}
include_once 'dbconnect.php';
if(isset($_POST['signup'])) {
	$ng_word = ["野獣先輩","淫夢","うんこ","ウンコ","う〇こ","ウ〇コ","ちんこ","チンコ","ち〇こ","チ〇コ","まんこ","マンコ","ま〇こ","マ〇コ","おっぱい","死ね"];
	$user = htmlspecialchars($_POST['user']);
	if (array_search($user, $ng_word)) {
		?><div class="alert alert-danger" role="alert">このユーザー名は登録できません</div><?php
			exit();
	}
	$pass = htmlspecialchars($_POST['pass']);
	$pass = password_hash($pass, PASSWORD_DEFAULT);
	$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
	$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$sql = "SELECT COUNT(*) FROM users WHERE user=?";
	$stmt = $pdo->prepare($sql);
	if($stmt->execute([$user])) {
		$result = $stmt->fetchColumn();
		if ($result != 0) {
			?><div class="alert alert-danger" role="alert">このユーザー名は既に登録されています</div><?php
			exit();
		}
	}
	$sql = "INSERT INTO users(user,pass) VALUES(?, ?)";
	$stmt = $pdo->prepare($sql);
	if($stmt->execute([$user, $pass])) {
		?><div class="alert alert-success" role="alert">登録しました</div><?php
	} else {
		?><div class="alert alert-danger" role="alert">エラーが発生しました</div><?php
	}
}
?>
<br>
<br>
<h2>データについて</h2>
<p>　ユーザーから取得した位置情報は緯度経度情報から住所を特定するためだけに使用します。<br>
　住所は〇〇県××市△△□丁目のようなフォーマットで、番地以降の住所を取得することはできません。また、どのユーザーがどの住所を取得したのかなどの情報がサーバに残ることはありません。</p>
<p>　ID・PW以外でユーザーごとに記録される情報は、全ユーザーの中で一番最初に訪れた都道府県・市区町村・町域の数だけです。</p>
<p>　住所データベースにおいて、誰も訪れたことがない町域を0、誰かが訪れたことがある町域を1として記録しています。この値がユーザーが住所を取得した段階で0であればscoreに加算します。scoreはマイページやランキングから確認することができますので、そちらからご確認ください。※ユーザー登録を行った方限定の機能<br>
<p>　心配する方もいらっしゃるかと思いますので明記しますが、私が管理するデータの全てにおいて個人の住所を特定することはできません。ご安心ください。</p>
<br>
<h2>利用規約のような何か</h2>
<p>　ほかの人に迷惑にならないように利用してください。一応少しだけ。</p>
<h3>本サービスの提供の停止等</h3>
<ol>
<li>以下のいずれかの事由があると判断した場合、ユーザーに事前に通知することなく本サービスの全部または一部の提供を停止または中断することができるものとします。</li>
<ol>
<li>本サービスにかかるコンピュータシステムの保守点検または更新を行う場合</li>
<li>地震、落雷、火災、停電または天災などの不可抗力により、本サービスの提供が困難となった場合</li>
<li>コンピュータまたは通信回線等が事故により停止した場合</li>
<li>その他、管理人が本サービスの提供が困難と判断した場合</li>
</ol>
<li>管理人は、本サービスの提供の停止または中断により、ユーザーまたは第三者が被ったいかなる不利益または損害について、理由を問わず一切の責任を負わないものとします。</li>
</ol>
<h3>利用制限および登録抹消</h3>
<ol>
<li>以下の場合には、事前の通知なくユーザーに対して本サービスの全部もしくは一部の利用を制限し、またはユーザーとしての登録を抹消することができるものとします。</li>
<ol>
<li>ユーザー名に他社を不快にさせる文字列が含まれていると判断した場合</li>
<li>管理人が本サービスの利用を適当でないと判断した場合</li>
</ol>
<li>管理人は、本条に基づき管理人が行った行為によりユーザーに生じた損害について一切の責任を負いません。</li>
</ol>

<br>
<p>以上</p>
<br>
</form>
</body>
</html>