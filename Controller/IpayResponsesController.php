<?php
App::uses('IpayAppController', 'Ipay.Controller');
App::uses('IpayComponent', 'Ipay.Controller/Component');

/**
 * Class IpayResponsesController
 *
 * @property  IpayComponent $Ipay
 */
class IpayResponsesController extends IpayAppController
{
    public $components = array('Ipay.Ipay');

    public function beforeFilter()
    {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow('backend_post');
        }
        if (isset($this->Security) && $this->action == 'backend_post') {
            $this->Security->validatePost = false;
        }
    }

    public function backend_post()
    {
        $this->autoRender = false;

        $ipayResponse = $this->Ipay->processPaymentResponse();

        if ($ipayResponse->isSuccess()) {
            echo 'RECEIVEOK';
        }

        return;
    }

}