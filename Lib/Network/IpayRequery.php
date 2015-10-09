<?php
App::uses('IpayValidator', 'Ipay.Lib');
App::uses('CakeLog', 'Log');


class IpayRequery
{
    private $httpClient = null;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function uri()
    {
        return Configure::read('Ipay.requeryUrl');
    }

    public function query($data)
    {
        return [
            'MerchantCode' => $data['merchant_code'],
            'RefNo' => $data['ref_no'],
            'Amount' => number_format($data['amount'], 2),
        ];
    }

    public function request($data)
    {
        $response = $this->httpClient->post($this->uri(), $this->query($data));

        if (false === $response) {
            CakeLog::write('ipay', __('IO error in requery'));
            CakeLog::write('ipay', print_r($data, true));
            return 'Network error';
        }

        return $response->body;
    }

}