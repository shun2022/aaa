<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>国勢調査の結果を照会</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<a href="index.php">国勢調査トップに戻る</a>
<br><br><br><br>

<?php
$chimei = preg_replace('/^\xEF\xBB\xBF/', '', $_GET["timei"]);
echo "<h2><strong>".$chimei."</strong>の就業状況を、全国平均と比較してみました</h2><br><br><br>";
echo "😃の個数が多ければ多いほど、その産業で働く人の割合が多いことを示しています。（😃の個数＝平均からの乖離倍率）";
$dsn = 'mysql:dbname=toukei;host=localhost';
$user = 'root';
$password = '';
try{
    $dbh = new PDO($dsn, $user, $password);
    $dbh->query('SET NAMES utf8;');
    $sql1 = 'select * from tokei_setumei';
    
}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}
?>

<body>
<main>

    <table border="1">
        <?php
            $counter = 1;
            foreach ($dbh->query($sql1) as $row1) {
                $temp = preg_replace('/^\xEF\xBB\xBF/', '', $row1['name']);
                if ($temp == "chiiki_code") {
                    continue;
                    $counter += 1;
                }
                $sql2 = 'select '.$temp.' from tokei where chiiki_name = "全国"';
                $sql3 = 'select '.$temp.' from tokei where chiiki_name = "'.$chimei.'"';
                $stmt2 = $dbh->query($sql2);
                $stmt3 = $dbh->query($sql3);
                $result2 = $stmt2->fetch();
                $result3 = $stmt3->fetch();
                
                if ($counter > 2) {
                    $data1 = (double)$result2[0];
                    $data2 = (double)$result3[0];
                    if ($data2/$data1 > 1) {
                        $kosuu = (int)($data2/$data1);
                        $data3 = "";
                        for ($i=0; $i < $kosuu; $i++) { 
                            $data3 = $data3."😃";
                        }
                    }else {
                        $data3 = "";
                    }
                }else {
                    $data1 = $result2[0];
                    $data2 = $result3[0];
                    $data3 = "";                   
                }
                echo '<tr>';
                echo '<td>'.$row1['setumei'].'</td>';
                echo '<td nowrap>'.$data1.'</td>';
                echo '<td>'.$data2.'</td>';
                echo '<td width="125">'.$data3.'</td>';
                echo '</tr>';
                $counter += 1;
            }
        ?>
    </table>

</main>
</body>

<?php
$dbh = null;
?>


</html>