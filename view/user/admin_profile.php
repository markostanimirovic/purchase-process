<?php
$title = 'Profil administratora';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4""><i class="fa fa-user" aria-hidden="true"></i> Profil administratora</h1>
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
                               value="<?= $administrator->getName(); ?>">
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
                        <label for="surname" class="col-form-label">Prezime <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="surname" placeholder="Prezime" name="surname"
                               value="<?= $administrator->getSurname(); ?>">
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
                </div>
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="username" class="col-form-label">Korisničko ime <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" placeholder="Korisničko ime"
                               name="username" value="<?= $administrator->getUsername(); ?>">
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
                               value="<?= $administrator->getEmail(); ?>">
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
                               value="<?php if (!is_null($administrator->getOldPassword())) {
                                   echo $administrator->getOldPassword();
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
                               value="<?php if (!is_null($administrator->getNewPassword())) {
                                   echo $administrator->getNewPassword();
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
                               value="<?php if (!is_null($administrator->getNewRepeatedPassword())) {
                                   echo $administrator->getNewRepeatedPassword();
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

    <script>
        $(document).ready(function () {
            $('.cancel').on('click', function () {
                window.location = '/';
            });
        });

    </script>

<?php
$javascript = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params,
    array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript)));