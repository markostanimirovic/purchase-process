<?php
$title = 'Dodavanje novog zaposlenog';

ob_start();
?>

    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Dodavanje novog zaposlenog</h1>
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
            <form action="/employee/insert/" method="post" novalidate autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name" class="col-form-label">Ime <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" placeholder="Ime" name="name"
                               value="<?php if (isset($employee)) echo $employee->getName(); ?>">
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
                               value="<?php if (isset($employee)) echo $employee->getSurname(); ?>">
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
                        <label for="username" class="col-form-label">Korisničko ime <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" placeholder="Korisničko ime"
                               name="username" value="<?php if (isset($employee)) echo $employee->getUsername(); ?>">
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
                               value="<?php if (isset($employee)) echo $employee->getEmail(); ?>">
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
                        Klikom na dugme Sačuvaj zaposleni će na e-mail adresu dobiti konfiguracioni mejl sa korisničkim
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
                window.location = '/employee/';
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