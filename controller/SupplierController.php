<?php

namespace controller;


use helper\Generator;
use helper\Mailer;
use model\User;
use model\Supplier;
use modelRepository\PlaceRepository;
use modelRepository\SupplierRepository;

class SupplierController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
        $this->accessDeny(User::ADMINISTRATOR);
    }

    public function indexAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/admin_menu.php');
        echo $this->render('supplier/index.php', $params);
    }

    public function insertAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/admin_menu.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplier = new Supplier();

            $supplier->setUsername($_POST['username']);
            $supplier->setEmail($_POST['email']);
            $supplier->setPassword(Generator::getRandomPassword());
            $supplier->setRole(User::SUPPLIER);

            $supplier->setName($_POST['name']);
            $supplier->setPib($_POST['pib']);
            $supplier->setStreet($_POST['street']);
            $supplier->setStreetNumber($_POST['street-number']);

            $placeId = (!isset($_POST['place']) || !ctype_digit((string)$_POST['place'])) ? -1 : $_POST['place'];
            $placeRepository = new PlaceRepository();
            $place = $placeRepository->loadById($placeId);

            $supplier->setPlace($place);

            $result = $supplier->save();

            if (!empty($result)) {
                $params['errors'] = $result;
                $params['supplier'] = $supplier;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom unosa novog dobavljača!'));
            } else {
                $body = '<table border="1"><tr><td>Korisničko ime</td><td>' . $supplier->getUsername() . '</td></tr><tr><td>Lozinka</td><td>' . $supplier->getPassword() . '</td></tr></table>';
                $result = Mailer::sendMail($supplier->getEmail(), 'Konfiguracioni mejl', $body);
                if ($result === true) {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste dodali dobavljača {$supplier->getPib()} {$supplier->getName()}!"));
                } else {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'danger', 'alertText' => "<strong>Greška</strong> prilikom slanja konfiguracionog mejla dobavljaču {$supplier->getPib()} {$supplier->getName()}!"));
                }
                header("Location: /supplier/");
                exit();
            }
        }

        echo $this->render('supplier/insert.php', $params);
    }

    public function editAction($id)
    {
        if (!ctype_digit((string)$id)) {
            header("Location: /404notFound/");
            exit();
        }

        $supplierRepository = new SupplierRepository();
        $supplier = $supplierRepository->loadById($id);

        if (empty($supplier)) {
            header("Location: /404notFound/");
            exit();
        }

        $params = array();
        $params['menu'] = $this->render('menu/admin_menu.php');
        $params['supplier'] = $supplier;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplier->setUsername($_POST['username']);
            $supplier->setEmail($_POST['email']);

            $resetPassword = (isset($_POST['reset-password'])) ? $_POST['reset-password'] : null;
            $params['resetPassword'] = $resetPassword;
            if (isset($resetPassword)) {
                $supplier->setPassword(Generator::getRandomPassword());
            }

            $supplier->setName($_POST['name']);
            $supplier->setPib($_POST['pib']);
            $supplier->setStreet($_POST['street']);
            $supplier->setStreetNumber($_POST['street-number']);

            $placeId = (!isset($_POST['place']) || !ctype_digit((string)$_POST['place'])) ? -1 : $_POST['place'];
            $placeRepository = new PlaceRepository();
            $place = $placeRepository->loadById($placeId);

            $supplier->setPlace($place);

            $result = $supplier->save();

            if (!empty($result)) {
                $params['errors'] = $result;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom izmene dobavljača!'));
            } else {
                $body = '<p>Administrator je menjao Vaš profil.</p><table border="1"><tr><td>Korisničko ime</td><td>' . $supplier->getUsername() . '</td></tr><tr><td>Lozinka</td><td>' . $supplier->getPassword() . '</td></tr></table>';
                $result = Mailer::sendMail($supplier->getEmail(), 'Konfiguracioni mejl', $body);
                if ($result === true) {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste izmenili dobavljača {$supplier->getPib()} {$supplier->getName()}!"));
                } else {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'danger', 'alertText' => "<strong>Greška</strong> prilikom slanja konfiguracionog mejla dobavljaču {$supplier->getPib()} {$supplier->getName()}!"));
                }
                header("Location: /supplier/");
                exit();
            }
        }

        echo $this->render('supplier/edit.php', $params);
    }

    public function deactivateAction()
    {
        $id = json_decode($_POST['id']);
        if (!ctype_digit((string)$id)) {
            echo json_encode('false');
            exit();
        }

        $supplierRepository = new SupplierRepository();

        $supplier = $supplierRepository->loadById($id);

        if (empty($supplier)) {
            echo json_encode('false');
            exit();
        }

        $result = $supplier->deactivate();
        echo json_encode($result);
    }

    public function getAllSuppliersAction()
    {
        header('Content-type: application/json');

        $jsonArray = array();
        $supplierRepository = new SupplierRepository();
        $suppliers = $supplierRepository->load();
        foreach ($suppliers as $supplier) {
            $jsonObj = array();
            $jsonObj['id'] = $supplier->getId();
            $jsonObj['name'] = $supplier->getName();
            $jsonObj['pib'] = $supplier->getPib();
            $jsonObj['street'] = $supplier->getStreet();
            $jsonObj['streetNumber'] = $supplier->getStreetNumber();
            $jsonObj['place'] = $supplier->getPlace()->getZipCode() . ' ' . $supplier->getPlace()->getName();
            $jsonObj['username'] = $supplier->getUsername();
            $jsonObj['email'] = $supplier->getEmail();
            $jsonArray[] = $jsonObj;
        }

        $j['data'] = $jsonArray;
        echo json_encode($j);
    }
}