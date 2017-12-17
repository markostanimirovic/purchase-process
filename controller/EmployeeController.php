<?php

namespace controller;


use helper\Generator;
use helper\Mailer;
use model\Employee;
use model\User;
use modelRepository\EmployeeRepository;
use modelRepository\PositionRepository;

class EmployeeController extends LoginController
{
    public function __construct()
    {
        $this->notLoggedIn();
        $this->accessDenyIfNotIn([User::ADMINISTRATOR]);
    }

    public function indexAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/admin_menu.php');
        echo $this->render('employee/index.php', $params);
    }

    public function insertAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/admin_menu.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee = new Employee();

            $employee->setUsername($_POST['username']);
            $employee->setEmail($_POST['email']);
            $employee->setPassword(Generator::getRandomString());
            $employee->setRole(User::EMPLOYEE);

            $employee->setName($_POST['name']);
            $employee->setSurname($_POST['surname']);

            $positionId = (!isset($_POST['position']) || !ctype_digit((string)$_POST['position'])) ? -1 : $_POST['position'];
            $positionRepository = new PositionRepository();
            $position = $positionRepository->loadById($positionId);

            $employee->setPosition($position);

            $result = $employee->save();

            if (!empty($result)) {
                $params['errors'] = $result;
                $params['employee'] = $employee;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom unosa novog zaposlenog!'));
            } else {
                $body = '<table border="1"><tr><td>Korisničko ime</td><td>' . $employee->getUsername() . '</td></tr><tr><td>Lozinka</td><td>' . $employee->getPassword() . '</td></tr></table>';
                $result = Mailer::sendMail($employee->getEmail(), 'Konfiguracioni mejl', $body);
                if ($result === true) {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste dodali zaposlenog {$employee->getName()} {$employee->getSurname()}!"));
                } else {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'danger', 'alertText' => "<strong>Greška</strong> prilikom slanja konfiguracionog mejla zaposlenom {$employee->getName()} {$employee->getSurname()}!"));
                }
                header("Location: /employee/");
                exit();
            }
        }

        echo $this->render('employee/insert.php', $params);
    }

    public function editAction($id)
    {
        if (!ctype_digit((string)$id)) {
            header("Location: /404notFound/");
            exit();
        }

        $employeeRepository = new EmployeeRepository();
        $employee = $employeeRepository->loadById($id);

        if (empty($employee)) {
            header("Location: /404notFound/");
            exit();
        }

        $params = array();
        $params['menu'] = $this->render('menu/admin_menu.php');
        $params['employee'] = $employee;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee->setUsername($_POST['username']);
            $employee->setEmail($_POST['email']);

            $resetPassword = (isset($_POST['reset-password'])) ? $_POST['reset-password'] : null;
            $params['resetPassword'] = $resetPassword;
            if (isset($resetPassword)) {
                $employee->setPassword(Generator::getRandomString());
            }

            $employee->setName($_POST['name']);
            $employee->setSurname($_POST['surname']);

            $positionId = (!isset($_POST['position']) || !ctype_digit((string)$_POST['position'])) ? -1 : $_POST['position'];
            $positionRepository = new PositionRepository();
            $position = $positionRepository->loadById($positionId);

            $employee->setPosition($position);

            $result = $employee->save();

            if (!empty($result)) {
                $params['errors'] = $result;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom izmene zaposlenog!'));
            } else {
                $body = '<p>Administrator je menjao Vaš profil.</p><table border="1"><tr><td>Korisničko ime</td><td>' . $employee->getUsername() . '</td></tr><tr><td>Lozinka</td><td>' . $employee->getPassword() . '</td></tr></table>';
                $result = Mailer::sendMail($employee->getEmail(), 'Konfiguracioni mejl', $body);
                if ($result === true) {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste izmenili zaposlenog {$employee->getName()} {$employee->getSurname()}!"));
                } else {
                    $_SESSION['message'] = $this->render('global/alert.php',
                        array('type' => 'danger', 'alertText' => "<strong>Greška</strong> prilikom slanja konfiguracionog mejla zaposlenom {$employee->getName()} {$employee->getSurname()}!"));
                }
                header("Location: /employee/");
                exit();
            }
        }

        echo $this->render('employee/edit.php', $params);
    }

    public function deactivateAction()
    {
        $id = json_decode($_POST['id']);
        if (!ctype_digit((string)$id)) {
            echo json_encode('false');
            exit();
        }

        $employeeRepository = new EmployeeRepository();

        $employee = $employeeRepository->loadById($id);

        if (empty($employee)) {
            echo json_encode('false');
            exit();
        }

        $result = $employee->deactivate();
        echo json_encode($result);
    }

    public function getAllEmployeesAction()
    {
        header('Content-type: application/json');

        $jsonArray = array();
        $employeeRepository = new EmployeeRepository();
        $employees = $employeeRepository->load();
        foreach ($employees as $employee) {
            $jsonObj = array();
            $jsonObj['id'] = $employee->getId();
            $jsonObj['name'] = $employee->getName();
            $jsonObj['surname'] = $employee->getSurname();
            $jsonObj['position'] = $employee->getPosition()->getName();
            $jsonObj['username'] = $employee->getUsername();
            $jsonObj['email'] = $employee->getEmail();
            $jsonArray[] = $jsonObj;
        }

        $j['data'] = $jsonArray;
        echo json_encode($j, JSON_UNESCAPED_UNICODE);
    }
}