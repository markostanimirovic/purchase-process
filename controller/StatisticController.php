<?php

namespace controller;


use model\User;
use modelRepository\OrderFormRepository;

class StatisticController extends LoginController
{
    public function __construct()
    {
        $this->accessDenyIfNotIn([User::ADMINISTRATOR]);
    }

    public function orderFormAction()
    {
        $params = array();
        $params['menu'] = $this->render('menu/admin_menu.php');

        $orderFormRepository = new OrderFormRepository();
        $params['saved'] = $orderFormRepository->getNumberOfSaved();
        $params['sent'] = $orderFormRepository->getNumberOfSent();
        $params['reversed'] = $orderFormRepository->getNumberOfReversed();
        $params['approved'] = $orderFormRepository->getNumberOfApproved();
        $params['canceled'] = $orderFormRepository->getNumberOfCanceled();

        echo $this->render('statistic/order_form.php', $params);
    }
}