<?php
require_once("controller/route.php");
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQLiteGUI - <?= $route ?></title>
    <link rel="stylesheet" href="assets/primer.css">
    <link rel="stylesheet" href="assets/index.css">
    <script src="assets/js/jquery-3.6.0.js"></script>
    <script src="assets/js/script.js" type="module" defer></script>
</head>

<body>
    <header>
        <div class="Header px-6">
            <div class="Header-item">
                <h1>SQLiteGUI</h1>
            </div>
            <div class="Header-item Header-item--full mr-0">
            </div>
            <?php if ($route != 'login') : ?>
                <div class="Header-item mr-0">
                    <a href="controller/logout.php" class="Header-link">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <?php
    include('view/' . $route . '.php');
    ?>
    <footer></footer>
</body>

</html>