<?php
$temp1 = preg_replace('/^\xEF\xBB\xBF/', '', $_GET["timei"]);
echo $temp1."と、全国平均の就業状況を比較してみました";
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

    <table border="1">
        <?php
            foreach ($dbh->query($sql1) as $row1) {
                $temp = preg_replace('/^\xEF\xBB\xBF/', '', $row1['name']);
                if ($temp == "chiiki_code") {
                    continue;
                }
                $sql2 = 'select '.$temp.' from tokei where chiiki_name = "全国"';
                $sql3 = 'select '.$temp.' from tokei where chiiki_name = "'.$temp1.'"';
                $stmt2 = $dbh->query($sql2);
                $stmt3 = $dbh->query($sql3);
                $result2 = $stmt2->fetch();
                $result3 = $stmt3->fetch();
                echo '<tr>';
                    echo '<td>'.$row1['setumei'].'</td>';
                    echo '<td>'.$result2[0].'</td>';
                    echo '<td>'.$result3[0].'</td>';
                echo '</tr>';
            }
        ?>
    </table>
</body>




<?php
$dbh = null;
?>