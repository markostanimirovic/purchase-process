<?php
$title = 'Dodavanje novog kataloga';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Dodavanje novog kataloga</h1>
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
                    <div class="form-group col-md-4">
                        <label for="code" class="col-form-label">Šifra <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" placeholder="Šifra" name="code"
                               value="<?php if (isset($catalog)) echo $catalog->getCode(); ?>">
                        <span class="text-danger" id="code-error"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name" class="col-form-label">Naziv <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Naziv" name="name"
                               value="<?php if (isset($catalog)) echo $catalog->getName(); ?>">
                        <span class="text-danger" id="name-error"></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="date" class="col-form-label">Datum <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="date" placeholder="Datum" name="date"
                               value="<?php if (isset($catalog)) echo $catalog->getDate(); ?>"
                               readonly="readonly" style="background:white;">
                        <span class="text-danger" id="date-error"></span>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="product" class="col-form-label">Proizvod <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select id="product" class="form-control" name="product">
                                <option hidden></option>
                                <?php foreach ($products as $product) { ?>
                                    <option value="<?= $product->getCode(); ?>"><?= $product->getCode() . ' ' . $product->getName(); ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-btn">
                                <button class="add btn btn-primary" type="button"><i class="fa fa-plus"
                                                                                     aria-hidden="true"></i> Dodaj</button>
                            </span>
                        </div>
                        <span class="text-danger" id="product-error"></span>
                    </div>
                </div>
                <div class="form-row" id="tableDiv" <?php if (!isset($selectedProducts)) echo 'style="display:none"' ?>>
                    <div class="table-card card container col-md-12">
                        <table id="tableData" class="table table-hover table-bordered table-striped" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Šifra</th>
                                <th>Naziv</th>
                                <th>Jedinica mere</th>
                                <th>Cena</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($selectedProducts)) foreach ($selectedProducts as $selectedProduct) { ?>
                                <tr>
                                    <td><?= $selectedProduct->getCode(); ?></td>
                                    <td><?= $selectedProduct->getName(); ?></td>
                                    <td><?= $selectedProduct->getUnit(); ?></td>
                                    <td><?= $selectedProduct->getPrice(); ?></td>
                                    <td>
                                        <button type="button" class="delete-row btn btn-danger"><i class="fa fa-trash-o"
                                                                                                   aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <label class="col-form-label text-primary">
                        Klikom na dugme Sačuvaj katalog će biti sačuvan, ali ne i poslat. Moći će da se menja.
                        <br>
                        Klikom na dugme Pošalji katalog će biti sačuvan i poslat. Više neće moći da se menja.
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

    <script src="/js/catalog/insert.js"></script>
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