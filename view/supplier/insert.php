<?php
$title = 'Dodavanje novog dobavljača';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Dodavanje novog dobavljača</h1>
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

        <div class="card container">
            <form action="/supplier/insert/" method="post" novalidate autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name" class="col-form-label">Ime <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Ime" name="name"
                               value="<?php if (isset($supplier)) echo $supplier->getName(); ?>">
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
                    <div class="form-group col-md-6">
                        <label for="pib" class="col-form-label">PIB <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pib" placeholder="PIB" name="pib"
                               value="<?php if (isset($supplier)) echo $supplier->getPib(); ?>">
                        <?php
                        if (isset($errors['pib'])) {
                            foreach ($errors['pib'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="street" class="col-form-label">Ulica <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="street" placeholder="Ulica" name="street"
                               value="<?php if (isset($supplier)) echo $supplier->getStreet(); ?>">
                        <?php
                        if (isset($errors['street'])) {
                            foreach ($errors['street'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="street-number" class="col-form-label">Broj <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="street-number" placeholder="Broj"
                               name="street-number"
                               value="<?php if (isset($supplier)) echo $supplier->getStreetNumber(); ?>">
                        <?php
                        if (isset($errors['streetNumber'])) {
                            foreach ($errors['streetNumber'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="place" class="col-form-label">Mesto <span class="text-danger">*</span></label>
                        <select id="place" class="form-control" name="place">
                            <?php if (!empty($supplier) && !empty($supplier->getPlace())) { ?>
                                <option value="<?= $supplier->getPlace()->getId(); ?>">
                                    <?= $supplier->getPlace()->getZipCode() . ' ' . $supplier->getPlace()->getName(); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <?php
                        if (isset($errors['place'])) {
                            foreach ($errors['place'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="api-url" class="col-form-label">URL Web servisa gde su izloženi proizvodi dobavljača
                            <span class="text-danger">*</span>
                        </label>
                        <label class="col-form-label text-primary">
                            Napomena: Servis mora da vraća odgovor u JSON formatu.
                        </label>
                        <input type="text" class="form-control" id="api-url" placeholder="URL"
                               name="api-url"
                               value="<?php if (isset($supplier)) echo $supplier->getApiUrl(); ?>">
                        <?php
                        if (isset($errors['apiUrl'])) {
                            foreach ($errors['apiUrl'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="username" class="col-form-label">Korisničko ime <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" placeholder="Korisničko ime"
                               name="username" value="<?php if (isset($supplier)) echo $supplier->getUsername(); ?>">
                        <?php
                        if (isset($errors['username'])) {
                            foreach ($errors['username'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email" class="col-form-label">E-mail <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="email" placeholder="E-mail" name="email"
                               value="<?php if (isset($supplier)) echo $supplier->getEmail(); ?>">
                        <?php
                        if (isset($errors['email'])) {
                            foreach ($errors['email'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="form-row">
                    <label class="col-form-label text-primary">
                        Klikom na dugme Sačuvaj dobavljač će na e-mail adresu dobiti konfiguracioni mejl sa korisničkim
                        imenom i lozinkom.
                    </label>
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
    <script src="/js/plugin/select2/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.cancel').on('click', function () {
                window.location = '/supplier/';
            });

            $('#place').select2({
                width: '100%',
                allowClear: true,
                minimumInputLength: 2,
                multiple: false,
                placeholder: "Mesto",
                ajax: {
                    url: '/place/getAllPlacesByFilter/',
                    type: "GET",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            filter: params.term
                        };
                    }
                }
            });
        });

    </script>
<?php
$javascript = ob_get_clean();
ob_flush();
ob_start();
?>
    <link rel="stylesheet" href="/css/select2.min.css"/>
<?php
$css = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript, 'css' => $css)));