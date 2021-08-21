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
    <script src="assets/js/jquery-3.6.0.js"></script>
    <script src="assets/js/script.js" type="module" defer></script>
</head>

<body>
    <header class="position-fixed top-0 width-full">
        <div class="Header">
            <div class="Header-item">
                <a href="#" class="Header-link f4 d-flex flex-items-center">
                    <span>SQLiteGUI</span>
                </a>
            </div>
            <div class="Header-item">
                <input type="search" class="form-control Header-input" />
            </div>
            <div class="Header-item Header-item--full">
            </div>
            <div class="Header-item mr-0">
                <img class="avatar" height="20" alt="@octocat" src="https://github.com/octocat.png" width="20">
            </div>
        </div>
    </header>
    <?php
    include('view/' . $route . '.php');
    ?>
    <footer></footer>
</body>

</html>