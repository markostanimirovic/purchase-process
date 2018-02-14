<?php
$title = 'Dodavanje nove narudžbenice';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Dodavanje nove narudžbenice</h1>
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

        <div class="card container">
            <form novalidate autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="code" class="col-form-label">Šifra <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" placeholder="Šifra" name="code">
                        <span class="text-danger" id="code-error"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date" class="col-form-label">Datum <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="date" placeholder="Datum" name="date"
                               readonly="readonly" style="background:white;">
                        <span class="text-danger" id="date-error"></span>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="col-form-label">Dobavljač <span
                                    class="text-danger">*</span></label>
                        <div id="supplier-select-div">
                            <select class="form-control" id="supplier" name="supplier">
                                <option value="" hidden></option>
                                <option value="1">1</option>
                            </select>
                        </div>
                        <span class="text-danger" id="supplier-error"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label">Katalog <span class="text-danger">*</span></label>
                        <div id="catalog-select-div">
                            <select class="form-control" id="catalog" name="catalog">
                                <option hidden></option>
                            </select>
                        </div>
                        <span class="text-danger" id="catalog-error"></span>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="product" class="col-form-label">Proizvod <span class="text-danger">*</span></label>
                        <select id="product" class="form-control" name="product">
                        </select>
                        <span class="text-danger" id="product-error"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="unit" class="col-form-label">Merna jedinica</label>
                        <input type="text" class="form-control" id="unit" placeholder="Merna jedinica" name="unit"
                               readonly="readonly" style="background:white;">
                    </div>
                    <div class="col-md-3">
                        <label for="price" class="col-form-label">Cena</label>
                        <input type="text" class="form-control" id="price" placeholder="Cena" name="price"
                               readonly="readonly" style="background:white;">
                    </div>
                    <div class="col-md-1">
                        <label for="quantity" class="col-form-label">Količina</label>
                        <input type="text" class="form-control" id="quantity" name="quantity"
                               readonly="readonly" style="background:white;">

                    </div>
                    <div class="col-md-1">
                        <button type="button" class="add btn btn-primary" style="position:absolute;bottom:0.5px;">Dodaj</button>
                    </div>
                </div>
                <div class="form-row" id="tableDiv" style="display:none">
                    <div class="table-card card container col-md-12">
                        <table id="tableData" class="table table-hover table-bordered table-striped" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Šifra</th>
                                <th>Naziv</th>
                                <th>Jedinica mere</th>
                                <th>Cena</th>
                                <th>Količina</th>
                                <th>Iznos</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <label class="col-form-label text-primary">
                        Klikom na dugme Sačuvaj narudžbenica će biti sačuvana, ali ne i poslata. Moći će da se menja.
                        <br>
                        Klikom na dugme Pošalji narudžbenica će biti sačuvana i poslata. Više neće moći da se menja.
                    </label>
                </div>
                <hr>
                <button type="button" class="save btn btn-outline-primary"><i class="fa fa-floppy-o"
                                                                              aria-hidden="true"></i>
                    Sačuvaj
                </button>
                <button type="button" class="send btn btn-outline-success"><i class="fa fa-paper-plane-o"
                                                                              aria-hidden="true"></i>
                    Pošalji
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
    <script src="/js/plugin/select2/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="/js/plugin/dataTables/jquery.dataTables.min.js"></script>
    <script src="/js/plugin/dataTables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>

    <script src="/js/orderForm/insert.js"></script>
<?php
$javascript = ob_get_clean();
ob_flush();
ob_start();
?>
    <link rel="stylesheet" href="/css/select2.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.3/css/select.bootstrap.min.css" type="text/css">
<?php
$css = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript, 'css' => $css)));