<?php
$title = 'Prijava';
ob_start()
?>
    <div class="jumbotron">
        <div class="container" style="text-align: center">
            <h1 class="display-4">Prijava</h1>
            <h2 class="display-5">Informacioni sistem za upravljanje procesom nabavke</h2>
        </div>
    </div>

<?php
$header = ob_get_clean();
ob_flush();
ob_start();
?>
    <div class="container col-md-3">
        <?php if (isset($message)) echo $message; ?>
        <div align="center" class="card container">
            <form action="/login/" method="post" novalidate autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="username-email" class="col-form-label">Korisničko ime ili e-mail <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username-email"
                               name="username-email"
                               value="<?php if (isset($usernameEmail)) {
                                   echo $usernameEmail;
                               } else if (isset($_COOKIE['usernameEmail'])) {
                                   echo $_COOKIE['usernameEmail'];
                               };
                               ?>">
                        <?php
                        if (isset($errors['usernameEmail'])) {
                            foreach ($errors['usernameEmail'] as $error) {
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
                        <label for="password" class="col-form-label">Lozinka <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password"
                               value="<?php if (isset($password)) {
                                   echo $password;
                               } else if (isset($_COOKIE['password'])) {
                                   echo $_COOKIE['password'];
                               };
                               ?>">
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
                </div>
                <label class="form-check-label" for="remember">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember"
                        <?php if (isset($remember) || isset($_COOKIE['usernameEmail'])) {
                            echo 'checked';
                        } ?>>
                    Zapamti me
                </label>
                <hr>
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-sign-in" aria-hidden="true"></i>
                    Prijava
                </button>

            </form>
        </div>
    </div>
<?php
$content = ob_get_clean();
ob_flush();
echo render('base.php', array('title' => $title, 'header' => $header, 'content' => $content));