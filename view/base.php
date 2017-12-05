<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="/img/purchase-process.ico">

    <title><?php if (isset($title)) echo $title; ?></title>

    <?= render('global/css.php'); ?>
    <?php if (isset($css)) echo $css; ?>
</head>
<body>
<div class="menu">
    <?php if (isset($menu)) echo $menu; ?>
</div>
<div class="header">
    <?php if (isset($header)) echo $header; ?>
</div>
<div class="body">
    <?php if (isset($content)) echo $content; ?>
</div>
<div class="footer container">
        <hr>
        <span class="text-muted">© 2017 Marko Stanimirović FON</span>
</div>
<?= render('global/javascript.php'); ?>
<?php if (isset($javascript)) echo $javascript; ?>
</body>
</html>