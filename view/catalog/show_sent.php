<?php
$title = 'Katalozi';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Katalozi</h1>
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
                <th>Naziv</th>
                <th>Datum</th>
                <th>Dobavljač</th>
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
                    <td><?= $catalog->getSupplier()->getName(); ?></td>
                    <td>
                        <button type="button" data-id="<?= $catalog->getId(); ?>" class="view btn btn-outline-primary"
                                title="Prikaži">
                            <i class="fa fa-search-plus" aria-hidden="true"></i>
                        </button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Prikaz kataloga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="catalog-table">
                        <tbody>
                        <tr>
                            <th class="table-active">Šifra</th>
                            <td id="code-cell" class="col-md-6"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Naziv</th>
                            <td id="name-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Datum</th>
                            <td id="date-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Dobavljač</th>
                            <td class="table-active"></td>
                        </tr>
                        <tr>
                            <th class="table-active">Ime</th>
                            <td id="supplier-name-cell"></td>
                        </tr>
                        <tr>
                            <th class="table-active">PIB</th>
                            <td id="supplier-pib-cell"></td>
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
                        <th>Šifra</th>
                        <th>Naziv</th>
                        <th>Jedinica mere</th>
                        <th>Cena</th>
                        </thead>
                        <tbody id="products-tbody">

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
                var id = $(this).attr('data-id');
                $.get('/catalog/viewForEmployee/' + id, function (data) {
                    var response = JSON.parse(data);
                    if (response.type == "success") {
                        fillCatalogModal(response.catalog);
                        viewModal.modal('show');
                    } else {
                        $('.error-messages').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button>' + response.message + '</div>');

                    }
                });
            });

            function fillCatalogModal(catalog) {
                $('#code-cell').text(catalog.code);
                $('#name-cell').text(catalog.name);
                $('#date-cell').text(catalog.date);
                $('#supplier-name-cell').text(catalog.supplier.name);
                $('#supplier-pib-cell').text(catalog.supplier.pib);
                $('#street-and-number-cell').text('Ulica: ' + catalog.supplier.street + ", Broj: " + catalog.supplier.streetNumber);
                $('#place-cell').text(catalog.supplier.placeZipCode + ' ' + catalog.supplier.placeName);
                $('#products-tbody').text('');
                for (var i = 0; i < catalog.products.length; i++) {
                    $('#products-tbody').append('<tr><td>' + catalog.products[i].code + '</td><td>'
                        + catalog.products[i].name + '</td><td>' + catalog.products[i].unit +
                        '</td><td>' + catalog.products[i].price + '</td></tr>');
                }
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