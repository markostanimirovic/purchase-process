<?php
$title = 'Izmena pozicije';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Izmena pozicije</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>

    <div class="container col-md-5">
        <?php if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        } ?>

        <div class="card container">
            <form action="/position/edit/<?= $position->getId(); ?>" method="post" novalidate autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="name" class="col-form-label">Naziv <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Naziv" name="name"
                               value="<?= $position->getName(); ?>">
                        <?php
                        if (isset($errors['name'])) {
                            foreach ($errors['name'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                    Sačuvaj
                </button>
                <button type="button" class="cancel btn btn-outline-secondary"><i class="fa fa-times"
                                                                                  aria-hidden="true"></i> Odustani
                </button>
            </form>
        </div>
    </div>

<?php
$content = ob_get_clean();
ob_flush();
ob_start();
?>
    <script>
        $(document).ready(function () {
            $('.cancel').on('click', function () {
                window.location = '/position/';
            });
        });
    </script>
<?php
$javascript = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript)));