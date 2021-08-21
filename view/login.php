<main>
    <h3>データベースの選択</h3>
    <p id="path"></p>
    <ul id="list">
    </ul>
    <input type="text" name="new">.db
    <button>作成</button>
    <hr>
    <h3>選択済みのデータベース</h3>
    <form action="controller/login.php" method="post">
        <p id="db"></p>
        <input type="hidden" name="name">
        <button type="submit">接続する</button>
    </form>
</main>