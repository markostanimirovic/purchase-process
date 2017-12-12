<?php

namespace controller;


use common\base\BaseController;
use common\Session;
use model\User;
use modelRepository\UserRepository;

class LoginController extends BaseController
{
    public function indexAction()
    {
        $this->loggedIn();

        $params = array();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = array();

            $usernameEmail = trim($_POST['username-email']);
            $password = $_POST['password'];
            $remember = (isset($_POST['remember'])) ? $_POST['remember'] : null;

            $userRepository = new UserRepository();
            $user = $userRepository->getUserByUsernameOrEmail($usernameEmail);

            if (strlen($usernameEmail) === 0) {
                $errors['usernameEmail'][] = 'Korisničko ime ili e-mail ne sme da bude prazno polje.';
            } else {
                if (is_null($user)) {
                    $errors['usernameEmail'][] = 'Korisnik sa unetim korisničkim imenom ili e-mailom ne postoji.';
                }
            }

            if (strlen($password) === 0) {
                $errors['password'][] = 'Lozinka ne sme da bude prazno polje.';
            } else {
                if (!is_null($user) && $user->getPassword() !== $password) {
                    $errors['password'][] = 'Pogrešna lozinka.';
                }
            }

            if (!empty($errors)) {
                $params['usernameEmail'] = $usernameEmail;
                $params['password'] = $password;
                $params['remember'] = $remember;
                $params['errors'] = $errors;
                $params['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom prijave na sistem!'));
            } else {
                $this->setUserSession($user);
                if (!is_null($remember)) {
                    $this->setCookieRemember($usernameEmail, $password);
                } else {
                    $this->unsetCookieRemember();
                }
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'success', 'alertText' => "Zdravo <strong>{$_SESSION['user']['username']}</strong>. Uspešno ste se prijavili na sistem!"));

                header("Location: /");
                exit();
            }
        }

        echo $this->render('global/login.php', $params);
    }

    public function logoutAction()
    {
        $this->unsetUserSession();
        header("Location: /login/");
    }

    protected function accessDeny($role)
    {
        if($_SESSION['user']['role'] !== $role) {
            header('Location: /404NotFound/');
        }
    }

    private function loggedIn()
    {
        if(isset($_SESSION['user'])) {
            header('Location: /');
        }
    }

    protected function notLoggedIn()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: /login/');
            exit();
        }
    }

    private function setUserSession(User $user)
    {
        session_regenerate_id(true);
        $_SESSION['user']['id'] = $user->getId();
        $_SESSION['user']['username'] = $user->getUsername();
        $_SESSION['user']['role'] = $user->getRole();
    }

    private function unsetUserSession()
    {
        session_regenerate_id(true);
        session_destroy();
    }

    private function setCookieRemember($usernameEmail, $password)
    {
        unset($_COOKIE['usernameEmail']);
        setcookie('usernameEmail', $usernameEmail, time() + (86400 * 30), "/");

        unset($_COOKIE['password']);
        setcookie('password', $password, time() + (86400 * 30), "/");
    }

    private function unsetCookieRemember()
    {
        if (isset($_COOKIE['usernameEmail'])) {
            unset($_COOKIE['usernameEmail']);
            setcookie('usernameEmail', null, -1, '/');
        }

        if (isset($_COOKIE['password'])) {
            unset($_COOKIE['password']);
            setcookie('password', null, -1, '/');
        }
    }
}