<?php
$title = 'Proces nabavke';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Aplikacija za proces nabavke</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params, array('title' => $title, 'header' => $header)));