<?php
$title = 'Purchase process';

ob_start();
?>
    <div class="jumbotron jumbotron-fluid">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Welcome to purchasing process application!</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params, array('title' => $title, 'header' => $header)));