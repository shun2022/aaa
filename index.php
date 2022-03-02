<?php
error_reporting(E_ALL & ~E_NOTICE);
$dsn = 'mysql:dbname=toukei;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8;');

$position = 0;
$msg = null;
if ((isset($_REQUEST["position"]) == true)	// ボタンが押された？
 && (isset($_REQUEST["reset"]) != true))	// リセットボタンでない？
{
	$position = $_REQUEST["position"];
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>国勢調査の結果を照会</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
  $(document).ready(function()
  {
    window.onload = function (){	$(window).scrollTop(<?php echo $position; ?>);}
    $("input[type=submit]").click(function()
    {
      var position = $(window).scrollTop();
      $("input:hidden[name=position]").val(position);
      $("#form").submit();
    });
  });
</script>
</head>
<body>
<h1>国勢調査の結果を見てみよう！</h1>
<p style="color: red;">５年に一度行われる国勢調査の結果は、総務省のHPで公開されています。</p>
<p>
「このあたりは海沿いだから、漁師さん🎣 が多いのかな。東京はIT技術者🧑‍💻がたくさんいるけれど、どのあたりにとくにたくさん住んでいるんだろう」
と思ったことはありませんか
</p>

<p>
国勢調査のなかでも、<strong>✨✨就業構造基本調査✨✨</strong>は、市区町村単位で人々が何の仕事をしているかわかります。
</p>

<p>
総務省のHPでデータベースを閲覧できますが、少々混みいっていて、わかりにくいです。
</p>

<p style="color:blue";>
本ページでは、シンプルに、「市区町村を選んだら、その地区の人々の職業の割合がわかる」<br>
という機能に特化しました。
</p>

<div style="background-color:#dfecf9;">
[出典]<br>
平成27年国勢調査就業状態等基本集計（総務省統計局）<br>
第6-3表　産業(大分類)，男女別15歳以上就業者数及び産業別割合 － 全国，都道府県，市区町村 <br>
<a href="https://www.e-stat.go.jp/stat-search/database?page=1&statdisp_id=0003175084">https://www.e-stat.go.jp/stat-search/database?page=1&statdisp_id=0003175084</a><br>
を加工して作成
</div>
<br><br><br><br><br><br>

<?php
require "yubin.php"; 
if(isset($_POST['yubin_code'])){
  $value_1 = $_POST['yubin_code'];
}else{
  $value_1 =1000001;
}
echo <<< EOM
<form action="index.php" method="post">
郵便番号(7桁ハイフン無し）を入力して下さい：<input type="tel" size=7 name="yubin_code" value="{$value_1}">
<input name="position" type="hidden" value="0">
<input type="submit" name="submitBtn" value="郵便番号で検索">
</form>
EOM ;

try {
  if (isset($_POST['yubin_code'])) {
    //郵便番号の検索で、戻り値がNULL、つまり、検索失敗ならばエラー
    $modori_array = codo_to_city($_POST['yubin_code']);
    if (is_null($modori_array[0])) {
      throw new Exception();
    }
    //データベースに地名があるかどうか、実際にSQLしてみる。
    $sql = 'select chiiki_name from tokei where chiiki_name="'.$modori_array[1].'"';
    $sth = $dbh -> query($sql);
    $aryList = $sth -> fetch(PDO::FETCH_ASSOC);
    if ($aryList) { 
      //何もしない 検索成功
    }else {
      //検索失敗　SQLにない。
      throw new Exception();
    }

    echo '<a href="get.php?timei='.$modori_array[1].'">';
    echo $modori_array[0].$modori_array[1];
    echo "</a>";
    echo "<br>";
    echo "↑↑↑↑↑↑↑↑クリックでデータ表示<br><br><br>";
  }
} catch (Exception $e) {//エラーメッセージ
  echo 'すみません、上手く検索できませんでした。😂😂😂
  <br>番号を変えてやり直すか、以下のリンクの中から目的の地名を選んで下さい。 ';
  echo "<br><br><br><br>";
}
?>

<?php 
$sql = 'select chiiki_code,chiiki_name from tokei';
foreach ($dbh->query($sql) as $row) {
  if ($row['chiiki_code'] % 1000 ==0) {
    echo "<br>";
    echo '<a href="get.php?timei='.$row['chiiki_name'].'" style="font-size:15pt;font-weight:bold;">';
    echo "{$row['chiiki_name']}  ";
    echo "</a>";
    echo ":";
  } else {
    echo '<a href="get.php?timei='.$row['chiiki_name'].'">';
    echo "{$row['chiiki_name']}  ";
    echo "</a>";
    echo ":";
  }
  

}


?>

<!--
<footer>
<div class="foot">
    なんでもいいから、とにかく始めることが大切だなぁ<br>
</div>
</footer>
-->

</body>
</html>