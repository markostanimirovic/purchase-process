<?php
$title = 'Izmena dobavljača';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Izmena dobavljača</h1>
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
            <form action="/supplier/edit/<?= $supplier->getId(); ?>" method="post" novalidate autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name" class="col-form-label">Ime <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Ime" name="name"
                               value="<?= $supplier->getName(); ?>">
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
                               value="<?= $supplier->getPib(); ?>">
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
                               value="<?= $supplier->getStreet(); ?>">
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
                               value="<?= $supplier->getStreetNumber(); ?>">
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
                        <select id="place" class="form-control" id="place" placeholder="Mesto" name="place">
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
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="username" class="col-form-label">Korisničko ime <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" placeholder="Korisničko ime"
                               name="username" value="<?= $supplier->getUsername(); ?>">
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
                               value="<?= $supplier->getEmail(); ?>">
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
                    <div class="form-group col-md-4">
                        <label for="old-password" class="col-form-label">Stara lozinka</label>
                        <input type="text" readonly class="form-control" id="old-password"
                               value="<?= $oldPassword; ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="password" class="col-form-label">Nova lozinka</label>
                        <input type="password" class="form-control" id="password" placeholder="Lozinka" name="password"
                               value="<?= $supplier->getPassword(); ?>">
                        <?php
                        if (isset($errors['password'])) {
                            foreach ($errors['password'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="repeated-password" class="col-form-label">Ponovite lozinku</label>
                        <input type="password" class="form-control" id="repeated-password"
                               placeholder="Ponovite lozinku" name="repeated-password"
                               value="<?= $supplier->getRepeatedPassword(); ?>">
                        <?php
                        if (isset($errors['repeatedPassword'])) {
                            foreach ($errors['repeatedPassword'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
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
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript)));