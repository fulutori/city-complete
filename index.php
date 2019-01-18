<?php
session_start();
include_once 'dbconnect.php';
if(!isset($_SESSION['user'])) {
	$user_flag = 0;
	$user_id = "unknown";
	$user = "";
} else {
	$user_flag = 1;
	$user_id = $_SESSION['user'];
	$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
	$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$sql = 'SELECT user FROM users WHERE user_id=?';
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user_id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$user = $result['user'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>city-complete</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
<div style="display: flex; justify-content: space-between; align-items: flex-end;">
<h2>city-complete</h2>
<?php if ($user!="") echo $user; ?>
</div>
<hr style="margin-top: 10px">
<script>
function get_location() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition( success, error, option);
		function success(position){
			var data = position.coords;
			var lat = data.latitude;
			//var lat = 33.8885760;
			var lng = data.longitude;
			//var lng = 130.7616810;
			var alt = data.altitude;
			var user_id = <?php echo json_encode($user_id); ?>;
			var text = "user_id="+user_id+"&ido="+lat+"&keido="+lng+"&alt="+alt;
			address.innerHTML = "取得中<br>　";
			$.post('get_town.php', text).done(function(data) {
				address.innerHTML = data;
			});
		}
		function error(error){
			var errorMessage = {
				0: "原因不明のエラーが発生しました。",
				1: "位置情報が許可されませんでした。",
				2: "位置情報が取得できませんでした。",
				3: "タイムアウトしました。",
			};
			document.getElementById('address').innerHTML = errorMessage[error.code];
		}
		var option = {"enableHighAccuracy": true, "timeout": 1000, "maximumAge": 1000,};
	} else {
		alert("現在地を取得できませんでした");
	}
}
get_location();
setInterval("get_location()",30000);
</script>
<div id="address"></div>
<br>
<br>
<br>※30秒ごとに自動で現在地を更新します
<?php
if ($user_flag == 0) {	
	echo '<br><br><script type="text/javascript">rakuten_design="slide";rakuten_affiliateId="157241b8.84da0b05.1633c25f.71ad5bc8";rakuten_items="ctsmatch";rakuten_genreId="0";rakuten_size="300x160";rakuten_target="_blank";rakuten_theme="gray";rakuten_border="on";rakuten_auto_mode="off";rakuten_genre_title="off";rakuten_recommend="on";rakuten_ts="1542991099697";</script><script type="text/javascript" src="https://xml.affiliate.rakuten.co.jp/widget/js/rakuten_widget.js"></script><br>';
	echo "\n<p>ユーザー登録をすると広告が表示されなくなります。<br>ユーザー登録は<a href=\"register.php\">こちら</a></p>\n<p>既に登録済みの方は<a href=\"login.php\">こちら</a>からログインしてください。</p>";
} else {
	echo '<p><a href="mypage.php">マイページ</a>　<a href="ranking.php">ランキング</a></p><p><a href="logout.php?logout">ログアウト</a></p>';
}
?>
<br>
</body>
</html>