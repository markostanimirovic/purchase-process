<?php
$title = 'Dobavljači';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Dobavljači</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>
    <div class="container">
        <button type="button" class="insert btn btn-outline-primary">
            <i class="fa fa-plus" aria-hidden="true"></i> Dodaj novog
        </button>
    </div>
<?php
$content = ob_get_clean();
ob_flush();
ob_start();
?>
    <script>
        $(document).ready(function () {
            $('.insert').on('click', function () {
                window.location = '/supplier/insert/';
            });
        });
    </script>
<?php
$javascript = ob_get_clean();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript)));