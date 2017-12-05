<?php

namespace controller;


use login\LoginController;
use model\Place;
use modelRepository\PlaceRepository;

class PlaceController extends LoginController
{
    public function indexAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/main_menu.php');
        echo $this->render('place/index.php', $params);
    }

    public function insertAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/main_menu.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $place = new Place();

            $place->setZipCode($_POST['zip-code']);
            $place->setName($_POST['name']);

            $result = $place->save();

            if (!empty($result)) {
                $params['errors'] = $result;
                $params['place'] = $place;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom unosa novog mesta!'));
            } else {
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste dodali mesto {$place->getZipCode()} {$place->getName()}!"));
                header("Location: /place/");
                exit();
            }
        }

        echo $this->render('place/insert.php', $params);
    }

    public function editAction($id)
    {
        if (!ctype_digit((string)$id)) {
            header("Location: /404notFound/");
            exit();
        }

        $params = array();
        $params['menu'] = $this->render('menu/main_menu.php');

        $placeRepository = new PlaceRepository();
        $place = $placeRepository->loadById($id);

        if (empty($place)) {
            header("Location: /404notFound/");
            exit();
        }

        $params['place'] = $place;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $place->setZipCode($_POST['zip-code']);
            $place->setName($_POST['name']);
            $result = $place->save();
            if (!empty($result)) {
                $params['errors'] = $result;
                $params['place'] = $place;
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'danger', 'alertText' => '<strong>Greška</strong> prilikom izmene mesta!'));
            } else {
                $_SESSION['message'] = $this->render('global/alert.php',
                    array('type' => 'success', 'alertText' => "<strong>Uspešno</strong> ste izmenili mesto {$place->getZipCode()} {$place->getName()}!"));
                header("Location: /place/");
                exit();
            }
        }

        echo $this->render('place/edit.php', $params);
    }

    public function deactivateAction()
    {
        $id = json_decode($_POST['id']);
        if (!ctype_digit((string)$id)) {
            echo json_encode('false');
            exit();
        }

        $placeRepository = new PlaceRepository();
        if ($placeRepository->isPlaceSelectedInSupplier($id)) {
            echo json_encode('false');
            exit();
        }

        $place = $placeRepository->loadById($id);

        if (empty($place)) {
            echo json_encode('false');
            exit();
        }

        $result = $place->deactivate();
        echo json_encode($result);
    }

    public function getAllPlacesAction()
    {
        $jsonArray = array();
        $placeRepository = new PlaceRepository();
        $places = $placeRepository->load();
        foreach ($places as $place) {
            $json = array();
            $json['id'] = $place->getId();
            $json['zipCode'] = $place->getZipCode();
            $json['name'] = $place->getName();
            $jsonArray[] = $json;
        }
        $j['data'] = $jsonArray;
        echo json_encode($j);
    }

    public function getAllPlacesByFilterAction()
    {
        $filter = trim($_POST['filter']);
        $placeRepository = new PlaceRepository();
        $places = $placeRepository->loadByFilter($filter);
        if (empty($places)) {
            $response = array('results' => array());
        } else {
            foreach ($places as $place) {
                $data[] = array('id' => $place->getId(), 'text' => $place->getZipCode() . ' ' . $place->getName());
            }
            $response = array('results' => $data);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}