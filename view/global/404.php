<?php
$title = 'Greška 404';

ob_start();
?>

    <div class="row">
        <div class="col-md-12">
            <div class="text-danger error-template">
                <p class="h1">Greška 404</p>
                <div class="error-details">
                    <p class="h4">Tražena stranica ne postoji!</p>
                </div>
                <div class="error-actions">
                    <a href="/" class="btn btn-outline-danger btn-lg">Početna stranica</a>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params, array('title' => $title, 'content' => $content)));