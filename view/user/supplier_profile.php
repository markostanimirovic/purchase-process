<?php
$title = 'Profil dobavljača';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4""><i class="fa fa-user" aria-hidden="true"></i> Profil dobavljača</h1>
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
            <form action="/user/profile/" method="post" novalidate autocomplete="off">
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
                        <select id="place" class="form-control" id="place" name="place">
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
                        <label for="username" class="col-form-label">Korisničko ime</label>
                        <input type="text" class="form-control" id="username" readonly
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
                        <label for="email" class="col-form-label">E-mail</label>
                        <input type="text" class="form-control" id="email" readonly name="email"
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
                        <input type="password" class="form-control" id="old-password" placeholder="Stara lozinka"
                               name="old-password"
                               value="<?php if (!is_null($supplier->getOldPassword())) {
                                   echo $supplier->getOldPassword();
                               } ?>">
                        <?php
                        if (isset($errors['oldPassword'])) {
                            foreach ($errors['oldPassword'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="new-password" class="col-form-label">Nova lozinka</label>
                        <input type="password" class="form-control" id="new-password" placeholder="Nova lozinka"
                               name="new-password"
                               value="<?php if (!is_null($supplier->getNewPassword())) {
                                   echo $supplier->getNewPassword();
                               } ?>">
                        <?php
                        if (isset($errors['newPassword'])) {
                            foreach ($errors['newPassword'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="new-repeated-password" class="col-form-label">Ponovite lozinku</label>
                        <input type="password" class="form-control" id="new-repeated-password"
                               placeholder="Ponovite lozinku" name="new-repeated-password"
                               value="<?php if (!is_null($supplier->getNewRepeatedPassword())) {
                                   echo $supplier->getNewRepeatedPassword();
                               } ?>">
                        <?php
                        if (isset($errors['newRepeatedPassword'])) {
                            foreach ($errors['newRepeatedPassword'] as $error) {
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
    <script src="/js/plugin/select2/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.cancel').on('click', function () {
                window.location = '/';
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