<?php
$title = 'Dodavanje novog dobavljača';

ob_start();
?>

    <div class="jumbotron jumbotron-fluid">
        <div class="container" style="text-align: center">
            <h1 class="display-4"">Dodavanje novog dobavljača</h1>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>
    <div class="card container">
        <form action="/supplier/insert/" method="post" novalidate autocomplete="off">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name" class="col-form-label">Ime</label>
                    <input type="text" class="form-control" id="name" placeholder="Ime" name="name">
                </div>
                <div class="form-group col-md-6">
                    <label for="pib" class="col-form-label">PIB</label>
                    <input type="text" class="form-control" id="pib" placeholder="PIB" name="pib">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="street" class="col-form-label">Ulica</label>
                    <input type="text" class="form-control" id="street" placeholder="Ulica" name="street">
                </div>
                <div class="form-group col-md-4">
                    <label for="number" class="col-form-label">Broj</label>
                    <input type="text" class="form-control" id="number" placeholder="Broj" name="number">
                </div>
                <div class="form-group col-md-4">
                    <label for="place" class="col-form-label">Mesto</label>
                    <input type="text" class="form-control" id="place" placeholder="Mesto" name="place">
                </div>
            </div>
            <hr>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="username" class="col-form-label">Korisničko ime</label>
                    <input type="text" class="form-control" id="username" placeholder="Ime" name="username">
                </div>
                <div class="form-group col-md-6">
                    <label for="email" class="col-form-label">E-mail</label>
                    <input type="text" class="form-control" id="email" placeholder="E-mail" name="email">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="password" class="col-form-label">Lozinka</label>
                    <input type="password" class="form-control" id="password" placeholder="Lozinka" name="password">
                </div>
                <div class="form-group col-md-6">
                    <label for="repeated-password" class="col-form-label">Ponovi lozinku</label>
                    <input type="password" class="form-control" id="repeated-password" placeholder="Ponovi lozinku"
                           name="repeated-password">
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-outline-primary">Sačuvaj</button>
            <button type="button" class="cancel btn btn-outline-secondary">Odustani</button>
        </form>
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
            })
        });
    </script>
<?php
$javascript = ob_get_clean();
ob_flush();
echo render('base.php', array_merge($params, array('title' => $title, 'header' => $header, 'content' => $content, 'javascript' => $javascript)));
