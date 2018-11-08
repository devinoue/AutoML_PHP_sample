<?php

// テスト・予測用画像のパス
$image_path = "predict.jpg";

//プロジェクトID
$projectID = "automl-vision";
//データセットID
$datasetID = "ICN";

// 認証用トークン
$token = "";

$image = base64_encode(file_get_contents($image_path));

$body = [
    "payload" => [
        "image" => [
            "imageBytes" => $image,
        ],
    ],
];
// リクエスト用のJSONを作成
$json = json_encode($body);

$url = "https://automl.googleapis.com/v1beta1/projects/{$projectID}/locations/us-central1/models/{$datasetID}:predict";

// リクエストを実行
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer $token"));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

curl_setopt($curl, CURLOPT_TIMEOUT, 15);
curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
$res1 = curl_exec($curl);
$res2 = curl_getinfo($curl);

//エラーがあった場合には失敗とする
if (curl_errno($curl)) {
    return curl_errno($curl) . "Error";
}

curl_close($curl);

// 取得したデータ
$json_res = substr($res1, $res2["header_size"]);

$json_res = mb_convert_encoding($json_res, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json_res, true);


$displayName = $arr['payload'][0]['displayName'];
$score = round($arr['payload'][0]['classification']['score'] * 100);

print "displayName : $displayName<br>";
print "score : $score";

