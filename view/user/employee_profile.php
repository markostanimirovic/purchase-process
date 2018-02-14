<?php
$title = 'Profil zaposlenog';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"><i class="fa fa-user" aria-hidden="true"></i> Profil zaposlenog</h1>
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
                    <div class="form-group col-md-4">
                        <label for="name" class="col-form-label">Ime <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Ime" name="name"
                               value="<?= $employee->getName(); ?>">
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
                    <div class="form-group col-md-4">
                        <label for="surname" class="col-form-label">Prezime <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="surname" placeholder="Prezime" name="surname"
                               value="<?= $employee->getSurname(); ?>">
                        <?php
                        if (isset($errors['surname'])) {
                            foreach ($errors['surname'] as $error) {
                                ?>
                                <span class="text-danger"><?php echo $error; ?></span>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="position" class="col-form-label">Pozicija <span class="text-danger">*</span></label>
                        <select id="position" class="form-control" name="position">
                            <?php if (!empty($employee) && !empty($employee->getPosition())) { ?>
                                <option value="<?= $employee->getPosition()->getId(); ?>">
                                    <?= $employee->getPosition()->getName(); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <?php
                        if (isset($errors['position'])) {
                            foreach ($errors['position'] as $error) {
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
                               name="username" value="<?= $employee->getUsername(); ?>">
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
                               value="<?= $employee->getEmail(); ?>">
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
                               value="<?php if (!is_null($employee->getOldPassword())) {
                                   echo $employee->getOldPassword();
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
                               value="<?php if (!is_null($employee->getNewPassword())) {
                                   echo $employee->getNewPassword();
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
                               value="<?php if (!is_null($employee->getNewRepeatedPassword())) {
                                   echo $employee->getNewRepeatedPassword();
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

            $('#position').select2({
                width: '100%',
                allowClear: true,
                minimumInputLength: 2,
                multiple: false,
                placeholder: "Pozicija",
                ajax: {
                    url: '/position/getAllPositionsByFilter/',
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