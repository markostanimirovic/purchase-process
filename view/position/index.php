<?php
$title = 'Pozicije';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Pozicije</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>
    <div class="container col-md-7">
        <?php if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        } ?>

        <div id="alert-message">

        </div>

        <div align="center" style="padding-bottom: 10px">
            <div class="btn-group" role="group">
                <button type="button" class="insert btn btn-outline-success">
                    <i class="fa fa-plus" aria-hidden="true"></i> Dodaj
                </button>
                <button type="button" class="edit btn btn-outline-primary">
                    <i class="fa fa-pencil" aria-hidden="true"></i> Izmeni
                </button>
                <button type="button" class="delete btn btn-outline-danger">
                    <i class="fa fa-trash-o" aria-hidden="true"></i> Obriši
                </button>
            </div>
        </div>

        <table id="tableData" class="table table-hover table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Naziv</th>
            </tr>
            </thead>
        </table>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Da li ste sigurni da želite da obrišete selektovi red?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Ne</button>
                    <button type="button" class="delete-confirmed btn btn-primary">Da</button>
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

            var deleteModal = $('#delete-modal');

            var table = $('#tableData').DataTable({
                ajax: "/position/getAllPositions/",
                processing: true,
                columns: [
                    {data: "name"}
                ],
                select: {
                    style: 'single'
                },
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

            $('.insert').on('click', function () {
                window.location = '/position/insert/';
            });

            $('.edit').on('click', function () {
                var row = table.row('.selected').data();
                if (row == null) {
                    <?php $warningAlert = render('global/alert.php',
                    array('type' => 'warning', 'alertText' => "Niste izabrali red za izmenu!")); ?>
                    $('#alert-message').html('<?= $warningAlert; ?>');
                } else {
                    window.location = '/position/edit/' + row['id'];
                }
            });

            $('.delete').on('click', function () {
                var row = table.row('.selected').data();
                if (row == null) {
                    <?php $warningAlert = render('global/alert.php',
                    array('type' => 'warning', 'alertText' => "Niste izabrali red za brisanje!")); ?>
                    $('#alert-message').html('<?= $warningAlert; ?>');
                } else {
                    deleteModal.modal('show');
                }
            });

            $('.delete-confirmed').on('click', function () {
                var id = table.row('.selected').data()['id'];
                $.post('/position/deactivate/', {id: id}, function (result) {
                    deleteModal.modal('hide');
                    if (result == "true") {
                        <?php $successAlert = render('global/alert.php',
                        array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste obrisali selektovani red!")); ?>
                        $('#alert-message').html('<?= $successAlert; ?>');
                        table.row('.selected').remove().draw();
                    } else {
                        <?php $dangerAlert = render('global/alert.php',
                        array('type' => 'danger', 'alertText' =>
                            "<strong>Greška</strong> prilikom brisanja selektovanog reda! Možda postoji zaposleni sa pozicijom koju ste želeli da obrišete.")); ?>
                        $('#alert-message').html('<?= $dangerAlert; ?>');
                    }
                });
            });

        });
    </script>
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