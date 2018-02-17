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
                        <input type="text" class="form-control" id="code" placeholder="Šifra" name="code"
                               value="<?php if (isset($orderForm)) echo $orderForm->getCode(); ?>">
                        <span class="text-danger" id="code-error"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date" class="col-form-label">Datum <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="date" placeholder="Datum" name="date"
                               readonly="readonly" style="background:white;"
                               value="<?php if (isset($orderForm)) echo $orderForm->getDate(); ?>">
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
                                <?php if (isset($orderForm)) { ?>
                                    <option selected value="<?= $orderForm->getSupplier()->getId(); ?>">
                                        <?= $orderForm->getSupplier()->getPib() . ' ' . $orderForm->getSupplier()->getName(); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <span class="text-danger" id="supplier-error"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label">Katalog <span class="text-danger">*</span></label>
                        <div id="catalog-select-div">
                            <select class="form-control" id="catalog" name="catalog">
                                <?php if (isset($orderForm)) foreach ($catalogs as $catalog) { ?>
                                    <option value="<?= $catalog->getId() ?>" <?php if ($catalog->getId() == $selectedCatalogId) echo 'selected'; ?>>
                                        <?= $catalog->getCode() . ' ' . $catalog->getName(); ?></option>
                                <?php } ?>
                                <option value="-1"></option>
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
                            <?php if (isset($orderForm)) $firstProduct = $products[0]; ?>
                            <?php if (isset($orderForm)) foreach ($products as $product) { ?>
                                <option value="<?= $product->getId() ?>" data-code="<?= $product->getCode(); ?>"
                                        data-name="<?= $product->getName(); ?>"
                                        data-price="<?= $product->getPrice(); ?>"
                                        data-unit="<?= $product->getUnit(); ?>">
                                    <?= $product->getCode() . ' ' . $product->getName(); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="unit" class="col-form-label">Merna jedinica</label>
                        <input type="text" class="form-control" id="unit" placeholder="Merna jedinica" name="unit"
                               readonly="readonly" style="background:white;"
                               value="<?php if (isset($orderForm)) echo $firstProduct->getUnit(); ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="price" class="col-form-label">Cena</label>
                        <input type="text" class="form-control" id="price" placeholder="Cena" name="price"
                               readonly="readonly" style="background:white;"
                               value="<?php if (isset($orderForm)) echo number_format((float)$firstProduct->getPrice(), 2, '.', ''); ?>">
                    </div>
                    <div class="col-md-1">
                        <label for="quantity" class="col-form-label">Količina</label>
                        <input type="text" class="form-control" id="quantity" name="quantity"
                               readonly="readonly" style="background:white;">

                    </div>
                    <div class="col-md-2">
                        <label for="amount" class="col-form-label">Iznos</label>
                        <input type="text" class="form-control" id="amount" name="amount"
                               readonly="readonly" style="background:white;"
                               value="<?php if (isset($orderForm)) {
                                   echo number_format((float)$firstProduct->getPrice(), 2, '.', '');
                               } else {
                                   echo '0';
                               } ?>">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="add btn btn-primary" style="margin-top: 38px">
                            Dodaj
                        </button>
                    </div>
                    <span class="text-danger" id="product-error" style="margin-left: 5px;"></span>
                </div>
                <div class="form-row" id="tableDiv" <?php if (!isset($orderForm)) echo 'style="display:none"'; ?>>
                    <div class="table-card card container col-md-12" style="margin-top: 10px;">
                        <table id="tableData" class="table table-hover table-bordered table-striped" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Šifra</th>
                                <th>Naziv</th>
                                <th>Jedinica mere</th>
                                <th>Cena</th>
                                <th title="Količinu možete menjati">Količina <i class="fa fa-pencil"
                                                                                aria-hidden="true"></i></th>
                                <th>Iznos</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($orderForm)) foreach ($orderForm->getItems() as $item) { ?>
                                <tr id="<?= $item->getProduct()->getId(); ?>">
                                    <td><?= $item->getProduct()->getCode(); ?></td>
                                    <td><?= $item->getProduct()->getName(); ?></td>
                                    <td><?= $item->getProduct()->getUnit(); ?></td>
                                    <td><?= number_format((float)$item->getProduct()->getPrice(), 2, '.', ''); ?></td>
                                    <td><a href="#"
                                           data-price="<?= $item->getProduct()->getPrice(); ?>"><?= $item->getQuantity(); ?></a>
                                    </td>
                                    <td><?= $item->getAmount(); ?></td>
                                    <td>
                                        <button type="button" class="delete-row btn btn-danger">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="form-group row" style="margin-left: 12px; margin-top: 10px;">
                            <label class="col-form-label">Ukupan iznos:</label>&nbsp;
                            <input type="text" class="form-control col-md-2" id="total-amount" name="total-amount"
                                   readonly="readonly" style="background:white;" value="<?php if (isset($orderForm)) {
                                echo $orderForm->getTotalAmount();
                            } else {
                                echo '0';
                            } ?>">
                        </div>
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

    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

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

    <!--    <link href="/css/bootstrap-editable.css" rel="stylesheet"/>-->

    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
          rel="stylesheet"/>

    <style>
        .editable-field {
            width: 100px !important;
        }

        .editable-error-block {
            position: absolute !important;
            margin-top: 30px !important;
            color: #dc3545 !important;
        }

        .editable-click {
            color: #212529 !important;
            border-bottom: none !important;
        }

        .editable-unsaved {
            font-weight: normal !important;
        }
    </style>
<?php
$css = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript, 'css' => $css)));