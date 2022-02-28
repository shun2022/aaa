<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>国勢調査の結果を照会</title>
  <link rel="stylesheet" type="text/css" href="style.css">
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

<p>
[出典]<br>
平成27年国勢調査就業状態等基本集計（総務省統計局）<br>
第6-3表　産業(大分類)，男女別15歳以上就業者数及び産業別割合 － 全国，都道府県，市区町村 <br>
<a href="https://www.e-stat.go.jp/stat-search/database?page=1&statdisp_id=0003175084">https://www.e-stat.go.jp/stat-search/database?page=1&statdisp_id=0003175084</a><br>
を加工して作成
</p>

<?php 
$dsn = 'mysql:dbname=toukei;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$dbh->query('SET NAMES utf8;');
$sql = 'select chiiki_name from tokei';
foreach ($dbh->query($sql) as $row) {
    echo <<<EOM
    <a href="get.php?timei={$row['chiiki_name']}">
    {$row['chiiki_name']}  
    </a>
    EOM;
    echo ":";
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