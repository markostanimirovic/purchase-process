<?php
$title = 'Proizvodi';

ob_start();
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Proizvodi</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>
    <div class="container col-md-9">
        <div class="vrednost"></div>

        <div align="center" style="padding-bottom: 10px">
            <label for="currency">Cena je u valuti:</label>
            <select class="form-control col-md-1 col-md-offset-5" id="currency">
                <option value="rsd" selected>RSD</option>
                <option value="eur">EUR</option>
                <option value="chf">CHF</option>
                <option value="usd">USD</option>
            </select>
        </div>

        <table id="tableData" class="table table-hover table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Šifra</th>
                <th>Naziv</th>
                <th>Jedinica mere</th>
                <th>Cena</th>
            </tr>
            </thead>
        </table>
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
                ajax: "/product/getAllProducts/",
                processing: true,
                columns: [
                    {data: "code"},
                    {data: "name"},
                    {data: "unit"},
                    {data: "price"}
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

            var previous, present;

            $("#currency").on('focus', function () {
                previous = this.value;
            }).change(function () {
                present = this.value;
                $.ajax({
                    url: "https://api.kursna-lista.info/f53ec1381124cf3ac11a0ac413c7ee76/konvertor/" + previous + "/" + present + "/1",
                    type: "GET"
                }).then(function (data) {
                    table.rows().every(function (rowId) {
                        table.cell(rowId, 3).data((table.cell(rowId, 3).data() * data.result.value).toFixed(2));
                    }).draw();
                });
                previous = present;
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