<?php
$title = '404 Not Found';

ob_start();
?>

    <div class="row">
        <div class="col-md-12">
            <div class="text-danger error-template">
                <p class="h1">Oops!</p>
                <p class="h2">404 Not Found</p>
                <div class="error-details">
                    Sorry, an error has occured, Requested page not found!
                </div>
                <div class="error-actions">
                    <a href="/" class="btn btn-outline-danger btn-lg">Home Page</a>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params, array('title' => $title, 'content' => $content)));