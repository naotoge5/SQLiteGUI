<!-- Routing -->
<?php
require_once('controller/autoload.php');

$uri = $_SERVER['REQUEST_URI'];
$route = Route::on($uri);
?>
<?php if (is_object($route)) : ?>
    <?php
    $title = 'SQLiteGUI - Login';
    if (isset($_SESSION['db'])) {
        $db = unserialize($_SESSION['db']);
        $title = $db->getName();
    }
    if (!is_null($route->getTable())) {
        $title .= ' - ' . $route->getTable();
    }
    ?>
    <!DOCTYPE html>
    <html lang="ja" data-color-mode="auto" data-light-theme="light" data-dark-theme="dark">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="<?= $route->getRelative() ?>assets/primer.css">
        <link rel="stylesheet" href="<?= $route->getRelative() ?>assets/index.css">
        <script src="<?= $route->getRelative() ?>assets/js/jquery-3.6.0.js"></script>
        <script src="<?= $route->getRelative() ?>assets/js/<?= $route->getPath() . '.js' ?>" type="module" defer></script>
    </head>

    <body>
        <header>
            <div class="Header px-6 __px-md-ex">
                <div class="Header-item">
                    <h1><?= $title ?></h1>
                </div>
                <div class="Header-item Header-item--full mr-0">
                </div>
            </div>
            <!-- 通知 -->
            <?php if (isset($_SESSION["flash"])) : ?>
                <div class="flash flash-success my-4 mx-6 __mx-md-ex"><svg class="octicon octicon-shield-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd' clip-rule=" evenodd" d="M8.53336 0.133063C8.18645 0.0220524 7.81355 0.0220518 7.46664 0.133062L2.21664 1.81306C1.49183 2.045 1 2.71878 1 3.4798V6.99985C1 8.5659 1.31923 10.1823 2.3032 11.682C3.28631 13.1805 4.88836 14.4946 7.33508 15.5367C7.75909 15.7173 8.24091 15.7173 8.66493 15.5367C11.1116 14.4946 12.7137 13.1805 13.6968 11.682C14.6808 10.1823 15 8.5659 15 6.99985V3.4798C15 2.71878 14.5082 2.045 13.7834 1.81306L8.53336 0.133063ZM7.92381 1.5617C7.97336 1.54584 8.02664 1.54584 8.07619 1.5617L13.3262 3.2417C13.4297 3.27483 13.5 3.37109 13.5 3.4798V6.99985C13.5 8.35818 13.2253 9.66618 12.4426 10.8592C11.6591 12.0535 10.3216 13.2007 8.07713 14.1567C8.02866 14.1773 7.97134 14.1773 7.92287 14.1567C5.67838 13.2007 4.34094 12.0535 3.55737 10.8592C2.77465 9.66618 2.5 8.35818 2.5 6.99985V3.4798C2.5 3.37109 2.57026 3.27483 2.67381 3.2417L7.92381 1.5617ZM11.2803 6.28021C11.5732 5.98731 11.5732 5.51244 11.2803 5.21955C10.9874 4.92665 10.5126 4.92665 10.2197 5.21955L7.25 8.18922L6.28033 7.21955C5.98744 6.92665 5.51256 6.92665 5.21967 7.21955C4.92678 7.51244 4.92678 7.98731 5.21967 8.28021L6.71967 9.78021C7.01256 10.0731 7.48744 10.0731 7.78033 9.78021L11.2803 6.28021Z"></path>
                    </svg>
                    <?= $_SESSION["flash"] ?>
                </div>
                <?php unset($_SESSION["flash"]); ?>
            <?php endif; ?>
            <!-- /通知 -->
        </header>
        <?php include('view/' . $route->getPath() . '.php'); ?>
    </body>

    </html>
<?php else : ?>
    <?php header('Location: ' . $route); ?>
<?php endif; ?>