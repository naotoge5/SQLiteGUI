<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQLiteGUI</title>
    <script src="assets/script.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <style>
        header {
            background-color: black;
            color: white;
            padding: 0 36px 0;
        }
    </style>
</head>

<body>
    <header>
        <h1>SQLiteGUI</h1>
    </header>
    <div class="database">
        <h4>データベースファイルの選択</h4>
        <table class="show"></table>
        <button class="delete">選択されたデータベースを削除</button>
        <h4>データベースの新規作成</h4>
        <input type="text" name="new">.db
        <button class="new">作成</button>
    </div>
    <hr>
    <div class="table">
        <h3>データベース名</h3>
        <h4>テーブルの選択</h4>
        <table class="show"></table>
</body>

</html>