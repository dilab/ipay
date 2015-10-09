<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('IpayStatusValidator', 'Ipay.Lib/Validator');
App::uses('IpaySignatureValidator', 'Ipay.Lib/Validator');
App::uses('IpayRequery', 'Ipay.Lib/Network');
App::uses('IpayResponse', 'Ipay.Model');

/**
 * Class IpayComponent
 *
 * @property IpayResponse $ipayResponse
 */
class IpayComponent extends Component
{

    private $ipayResponse = null;

    private $controller = null;

    public function startup(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function processPaymentResponse()
    {
        if (!$this->controller->request->is('post')) {
            return null;
        }

        $this->ipayResponse = ClassRegistry::init('Ipay.IpayResponse');

        $ipayStatusValidator = new IpayStatusValidator();
        $ipaySignatureValidator = new IpaySignatureValidator();
        $ipayRequery = new IpayRequery(new HttpSocket());

        $this->ipayResponse->addResponseValidator($ipayStatusValidator);
        $this->ipayResponse->addResponseValidator($ipaySignatureValidator);
        $this->ipayResponse->useRequeryClient($ipayRequery);

        $this->ipayResponse->process($this->controller->request->data);

        return $this->ipayResponse;
    }

}