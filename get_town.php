<?php
if (!isset($_POST['ido'])) {
	exit();
} else if (!isset($_POST['keido'])) {
	exit();
}
include_once 'dbconnect.php';
$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset=utf8';
$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$ido = $_POST['ido'];
$keido = $_POST['keido'];
$user_id = $_POST['user_id'];
$alt = $_POST['alt'];
$sql = 'SELECT * FROM coordinates WHERE abs(latitude - ?) < 0.01 AND abs(longitude - ?) < 0.01 ORDER BY abs(latitude - ?) + abs(longitude - ?) limit 1';
$stmt = $pdo->prepare($sql);
$stmt->execute([$ido, $keido, $ido, $keido]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$pref = $result['prefectures'];
$city = $result['city'];
$town = $result['town'];
$name = $result['name'];
if ($name != "") {
	echo $pref.$city.$town."(".$name.")<br>高度: ".round($alt, 2)."m";
} else {
	echo $pref.$city.$town."<br>高度: ".round($alt, 2)."m";
}
if ($user_id == "unknown") {
	exit();
}
$town = mb_ereg_replace('[一二三四五六七八九]丁目', '', $town);
$sql = 'SELECT flag FROM town WHERE prefectures=? AND city=? AND town=?';
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$pref, $city, $town])) {
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$flag = $result['flag'];
	if ($flag == 0) {
		$add_score = 0;
		$pref_flag = 0;
		$city_flag = 0;
		$sql = 'SELECT COUNT(prefectures) FROM town WHERE flag=1 AND prefectures=?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$pref]);
		$pref_num = $stmt->fetchColumn();
		if ($pref_num == 0) {
			$add_score += 20;
			$pref_flag = 1;
		}
		$sql = 'SELECT COUNT(city) FROM town WHERE flag=1 AND prefectures=? AND city=?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$pref, $city]);
		$city_num = $stmt->fetchColumn();
		if ($city_num == 0) {
			$add_score += 10;
			$city_flag = 1;
		}
		$add_score+=1;
		$sql = 'UPDATE users SET score=score+? , score_pref=score_pref+? , score_city=score_city+? , score_town=score_town+1 WHERE user_id=?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$add_score, $pref_flag, $city_flag, $user_id]);
		$sql = 'UPDATE town SET flag=1 WHERE prefectures=? AND city=? AND town=?';
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$pref, $city, $town]);
		//echo "<br>pref_num: ".$pref_num."<br>city_num: ".$city_num."<br>add_score: ".$add_score;
	}
}
?>