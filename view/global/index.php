<?php
$title = 'Informacioni sistem za upravljanje procesom nabavke';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Informacioni sistem za upravljanje procesom nabavke</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>
    <div class="container">
        <?php if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        } ?>
    </div>
<?php
$content = ob_get_clean();
ob_flush();

echo render('base.php', array_merge($params, array('title' => $title, 'header' => $header, 'content' => $content)));