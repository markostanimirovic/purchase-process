<?php
$title = 'Katalozi';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Katalozi</h1>
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

        <div class="error-messages">

        </div>

        <table id="tableData" class="table table-hover table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Šifra</th>
                <th>Naziv</th>
                <th>Datum</th>
                <th>Stanje</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($catalogs as $catalog) { ?>
                <tr <?php $state = $catalog->getState();
                if ($state == 'Poslat') {
                    echo 'class="table-success"';
                } else if ($state == 'Storniran') {
                    echo 'class="table-danger"';
                } ?>>
                    <td><?= $catalog->getCode(); ?></td>
                    <td><?= $catalog->getName(); ?></td>
                    <td><?= $catalog->getDate(); ?></td>
                    <td><?= $catalog->getState(); ?></td>
                    <td>
                        <?php if ($state != 'U pripremi') { ?>
                            <button type="button" title="Dodaj novi na osnovu postojećeg"
                                    class="add-on-existing btn btn-success"
                                    style="margin-right:1px" data-id="<?= $catalog->getId(); ?>"><i
                                        class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        <?php }
                        if ($state == 'Poslat') { ?>
                            <button type="button" title="Storniraj" class="reverse btn btn-danger"
                                    data-id="<?= $catalog->getId(); ?>">
                                <i class="fa fa-times" aria-hidden="true"></i></button>
                        <?php } else if ($state == 'U pripremi') { ?>
                            <button type="button" title="Izmeni" class="edit btn btn-outline-primary"
                                    style="margin-right:1px" data-id="<?= $catalog->getId(); ?>"><i class="fa fa-pencil"
                                                                                                    aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Pošalji" class="send btn btn-outline-success"
                                    style="margin-right:1px" data-id="<?= $catalog->getId(); ?>"><i
                                        class="fa fa-paper-plane-o" aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Obriši" class="delete btn btn-outline-danger"
                                    data-id="<?= $catalog->getId(); ?>">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Ne</button>
                    <button type="button" class="confirmed btn btn-primary">Da</button>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
ob_flush();
ob_start();
?>
    <script src="/js/plugin/dataTables/jquery.dataTables.min.js"></script>
    <script src="/js/plugin/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>

    <script src="/js/catalog/index.js"></script>

<?php
$javascript = ob_get_clean();
ob_flush();
ob_start();
?>

    <link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.3/css/select.bootstrap.min.css" type="text/css">

<?php
$css = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript, 'css' => $css)));