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

        <table id="tableData" class="table table-hover table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Šifra</th>
                <th>Datum</th>
                <th>Ukupan iznos</th>
                <th>Stanje</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orderForms as $orderForm) { ?>
                <tr <?php $state = $orderForm->getState();
                if ($state == 'Odobrena') {
                    echo 'class="table-success"';
                } else if ($state == 'Odbijena') {
                    echo 'class="table-danger"';
                } ?>>
                    <td><?= $orderForm->getCode(); ?></td>
                    <td><?= $orderForm->getDate(); ?></td>
                    <td><?= $orderForm->getTotalAmount(); ?></td>
                    <td><?= $state; ?></td>
                    <td>
                        <?php if ($state == 'Poslata') { ?>
                            <button type="button" title="Prikaži"
                                    class="view btn btn-outline-primary"
                                    data-id="<?= $orderForm->getId(); ?>" data-state="<?= $state; ?>"><i
                                        class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>
                        <?php } else { ?>
                            <button type="button" title="Prikaži"
                                    class="view btn btn-primary"
                                    data-id="<?= $orderForm->getId(); ?>" data-state="<?= $state; ?>"><i
                                        class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
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
                    <div id="buttons">

                    </div>
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

    <script>
        $(document).ready(function () {
            var table = $('#tableData').DataTable({
                processing: true,
                columnDefs: [
                    {orderable: false, targets: -1},
                    {width: "10%", targets: -1}
                ],
                language: {
                    "sProcessing": "Procesiranje u toku...",
                    "sLengthMenu": "Prikaži _MENU_ elemenata",
                    "sZeroRecords": "Nije pronađen nijedan rezultat",
                    "sInfo": "Prikaz _START_ do _END_ od ukupno _TOTAL_ elemenata",
                    "sInfoEmpty": "Prikaz 0 do 0 od ukupno 0 elemenata",
                    "sInfoFiltered": "(filtrirano od ukupno _MAX_ elemenata)",
                    "sInfoPostFix": "",
                    "sSearch": "Pretraga:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Početna",
                        "sPrevious": "Prethodna",
                        "sNext": "Sledeća",
                        "sLast": "Poslednja"
                    },
                    "select": {
                        "rows": ""
                    }
                }
            });

            var viewModal = $('#view-modal');

            $('#tableData').on('click', '.view', function () {
                $('#buttons').text('');
                var id = $(this).attr('data-id');
                var state = $(this).attr('data-state');

                if (state == 'Poslata') {
                    $('#buttons').html('<button class="approve btn btn-success" type="button" data-id="' + id + '">Odobri</button>' +
                        '<button style="margin-left: 10px;" class="cancel btn btn-danger" type="button" data-id="' + id + '">Odbij</button>');
                }

                $.get('/orderForm/viewForSupplier/' + id, function (data) {
                    var response = JSON.parse(data);
                    if (response.type == "success") {
                        fillOrderFormModal(response.orderForm);
                        viewModal.modal('show');
                    } else {
                        echoErrorMessage(response.message);
                    }
                });
            });

            $('#buttons').on('click', '.approve', function () {
                var id = $(this).attr('data-id');
                $.get('/orderForm/approve/' + id, function (data) {
                    var response = JSON.parse(data);
                    if (response.type == "success") {
                        window.location = /orderForm/;
                    } else {
                        echoErrorMessage(response.message);
                    }
                    viewModal.modal('hide');
                });
            });

            $('#buttons').on('click', '.cancel', function () {
                var id = $(this).attr('data-id');
                $.get('/orderForm/cancel/' + id, function (data) {
                    var response = JSON.parse(data);
                    if (response.type == "success") {
                        window.location = /orderForm/;
                    } else {
                        echoErrorMessage(response.message);
                    }
                    viewModal.modal('hide');
                });
            });

            function fillOrderFormModal(orderForm) {
                $('#code-cell').text(orderForm.code);
                $('#date-cell').text(orderForm.date);
                $('#total-amount-cell').text(orderForm.totalAmount);
                $('#items-tbody').text('');
                for (var i = 0; i < orderForm.items.length; i++) {
                    $('#items-tbody').append('<tr><td>' + orderForm.items[i].code + '</td><td>'
                        + orderForm.items[i].name + '</td><td>' + orderForm.items[i].unit +
                        '</td><td>' + orderForm.items[i].price.toFixed(2) + '</td><td>' + orderForm.items[i].quantity + '</td>' +
                        '<td>' + orderForm.items[i].amount + '</td></tr>');
                }
            }

            function echoErrorMessage(message) {
                $('.error-messages').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button>' + message + '</div>');
            }
        });
    </script>

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