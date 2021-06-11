<?php

if ($_GET['type'] == 0) {
}

$path = '../db/' . $_GET['database'];
$pdo = new PDO('sqlite:' . $path);
$array = array();

foreach ($pdo->query('SELECT name FROM sqlite_master WHERE type="table"') as $row) {
    array_push($array, $row);
}
echo json_encode($array);


/*

try {
    // プレースホルダ付のSQLクエリの処理を準備する。
    $stmt = $conn->prepare($query);
    // プレースホルダに値をセットして、クエリの処理を実行する。
    // セットする値をユーザーが指定した場合、必要に応じて値のチェックをしておくこと。
    // ここではdata1の値が3以上10以下のすべてのレコードを取得している。
    $stmt->execute(array(
        'min_data' => 3,
        'max_data' => 10
    ));
} catch (PDOException $e) {
    // エラー処理
}
// DBから返された値を格納する配列。
$ary = array();
// 1レコードずつ値を配列に格納していく。
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $ary[] = array(
        'id' => intval($row['id']),
        'data1' => $row['data1'],
        'data2' => $row['data2']
    );
}
*/