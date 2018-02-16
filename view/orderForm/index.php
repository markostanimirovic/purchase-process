<?php
$title = 'Narudžbenice';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Narudžbenice</h1>
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

        <div align="center" style="padding-bottom: 10px">
            <button type="button" class="insert btn btn-outline-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Dodaj
            </button>
        </div>

        <table id="tableData" class="table table-hover table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Šifra</th>
                <th>Datum</th>
                <th>PIB dobavljača</th>
                <th>Ime dobavljača</th>
                <th>Ukupan iznos</th>
                <th>Stanje</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orderForms as $orderForm) { ?>
                <tr  <?php $state = $orderForm->getState();
                if ($state == 'Odobrena') {
                    echo 'class="table-success"';
                } else if ($state == 'Odbijena') {
                    echo 'class="table-danger"';
                } ?>>
                    <td><?= $orderForm->getCode(); ?></td>
                    <td><?= $orderForm->getDate(); ?></td>
                    <td><?= $orderForm->getSupplier()->getPib(); ?></td>
                    <td><?= $orderForm->getSupplier()->getName(); ?></td>
                    <td><?= $orderForm->getTotalAmount(); ?></td>
                    <td><?= $state; ?></td>
                    <td>
                        <?php
                        if ($state == 'U pripremi') { ?>
                            <button type="button" title="Izmeni" class="edit btn btn-outline-primary"
                                    style="margin-right:1px" data-id="<?= $orderForm->getId(); ?>"><i
                                        class="fa fa-pencil"
                                        aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Pošalji" class="send btn btn-outline-success"
                                    style="margin-right:1px" data-id="<?= $orderForm->getId(); ?>"><i
                                        class="fa fa-paper-plane-o" aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Obriši" class="delete btn btn-outline-danger"
                                    data-id="<?= $orderForm->getId(); ?>">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                        <?php } else if ($state == 'Poslata') { ?>
                            <button type="button" title="Prikaži"
                                    class="view btn btn-outline-primary"
                                    style="margin-right:1px" data-id="<?= $orderForm->getId(); ?>"><i
                                        class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Dodaj novu na osnovu postojeće"
                                    class="add-on-existing btn btn-outline-success"
                                    style="margin-right:1px" data-id="<?= $orderForm->getId(); ?>"><i
                                        class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Storniraj" class="reverse btn btn-outline-danger"
                                    data-id="<?= $orderForm->getId(); ?>">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        <?php } else if ($state == 'Stornirana') { ?>
                            <button type="button" title="Prikaži"
                                    class="view btn btn-outline-primary"
                                    style="margin-right:1px" data-id="<?= $orderForm->getId(); ?>"><i
                                        class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Dodaj novu na osnovu postojeće"
                                    class="add-on-existing btn btn-outline-success"
                                    data-id="<?= $orderForm->getId(); ?>">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        <?php }  else if ($state == 'Odobrena') { ?>
                            <button type="button" title="Prikaži"
                                    class="view btn btn-primary"
                                    style="margin-right:1px" data-id="<?= $orderForm->getId(); ?>"><i
                                        class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Dodaj novu na osnovu postojeće"
                                    class="add-on-existing btn btn-success"
                                    data-id="<?= $orderForm->getId(); ?>">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        <?php }  else if ($state == 'Odbijena') { ?>
                            <button type="button" title="Prikaži"
                                    class="view btn btn-primary"
                                    style="margin-right:1px" data-id="<?= $orderForm->getId(); ?>"><i
                                        class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" title="Dodaj novu na osnovu postojeće"
                                    class="add-on-existing btn btn-success"
                                    data-id="<?= $orderForm->getId(); ?>">
                                <i class="fa fa-plus" aria-hidden="true"></i>
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

    <!-- Modal -->
    <div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Prikaz narudžbenice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="order-form-table">
                        <tbody>
                        <tr>
                            <th class="table-active">Šifra</th>
                            <td id="code-cell" class="col-md-6"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Datum</th>
                            <td id="date-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Ukupan iznos</th>
                            <td id="total-amount-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Dobavljač</th>
                            <td class="table-active"></td>
                        </tr>
                        <tr>
                            <th class="table-active">PIB</th>
                            <td id="supplier-pib-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Ime</th>
                            <td id="supplier-name-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Adresa</th>
                            <td id="street-and-number-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Mesto</th>
                            <td id="place-cell"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered" id="products-table">
                        <thead class="table-active">
                        <th>Šifra proizvoda</th>
                        <th>Naziv proizvoda</th>
                        <th>Jedinica mere</th>
                        <th>Cena</th>
                        <th>Količina</th>
                        <th>Iznos</th>
                        </thead>
                        <tbody id="items-tbody">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvori</button>
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

    <script src="/js/orderForm/index.js"></script>

<?php
$javascript = ob_get_clean();
ob_flush();
ob_start();
?>

    <link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.3/css/select.bootstrap.min.css" type="text/css">
    <style>
        #catalog-table td {
            text-align: center;
        }

        #catalog-table th {
            text-align: center;
        }

        #products-table td {
            text-align: center;
        }

        #products-table th {
            text-align: center;
        }
    </style>

<?php
$css = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript, 'css' => $css)));