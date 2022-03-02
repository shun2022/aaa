<?php
function codo_to_city($yubin_code){
    $postalCode = $yubin_code; //ここに好きな郵便番号を代入
    $url = 'http://zipcloud.ibsnet.co.jp/api/search?zipcode=' . $postalCode; //下7桁が任意の郵便番号になったurlを$urlに代入

    //urlからはファイルが返される

    $resJson = file_get_contents($url); //ファイルの内容を文字列に読み込む
    //$resJson = mb_convert_encoding($resJson, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'); //文字化け防止
    $res = json_decode($resJson,true); //第二引数を「true」にすると配列型に変換

    //返ってきた文字列を、それぞれの変数に代入して整理していく
    $prefecture = $res['results'][0]['address1'];
    $city = $res['results'][0]['address2'];
    //$city2 = $res['results'][0]['address3'];
    //$zipcode = $res['results'][0]['zipcode'];


    //出力
    return array($prefecture, $city);
}

//echo codo_to_city($_POST["yubin_code"]);
?>