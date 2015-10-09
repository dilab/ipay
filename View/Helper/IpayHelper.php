<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Router', 'Utility');
App::uses('IpaySignatureGenerator', 'Ipay.Lib/Utility');

class IpayHelper extends AppHelper
{
    public $helpers = array('Html', 'Form');

    private $userRequiredFields = array('RefNo', 'Amount', 'Currency', 'ProdDesc', 'UserName', 'UserEmail', 'UserContact', 'ResponseURL');

    private $config = array();

    public function __construct(View $View, $settings = array())
    {
        if (!Configure::check('Ipay')) {
            throw new NotFoundException('Ipay config is not found');
        }

        $this->config = Configure::read('Ipay');

        parent::__construct($View, $settings);
    }

    public function button($data, $buttonOptions = array(), $formOptions = array())
    {
        $this->checkRequiredUserFields($data);

        $data = $this->supplyDefaultFields($this->supplySignature($data));

        return $this->html($data, $buttonOptions, $formOptions);
    }

    private function html($data, $buttonOptions, $formOptions)
    {
        $html = $this->Form->create('Form', array_merge(['url' => $this->config['requestUrl']], $formOptions));
        foreach ($data as $field => $value) {
            $html .= $this->Form->hidden($field, array(
                'name' => $field,
                'value' => $value,
            ));
        }

        $buttonValue = isset($buttonOptions['value']) ? $buttonOptions['value'] : __('Submit');

        $html .= $this->Form->submit($buttonValue, $buttonOptions);

        $html .= $this->Form->end();

        return $html;
    }

    private function checkRequiredUserFields($data)
    {
        $suppliedFields = array_keys($data);
        $diff = array_diff($this->userRequiredFields, $suppliedFields);

        if (count($diff) > 0) {
            throw new NotFoundException(__('Field %s not found', current($diff)));
        }
    }

    private function supplyDefaultFields($data)
    {
        $defaults = array(
            'MerchantCode' =>
                $this->config['merchantCode'],
            'BackendURL' =>
                Router::url(array('controller' => 'ipay_responses', 'action' => 'backend_post', 'plugin' => 'ipay'), true),
        );
        $data = array_merge($defaults, $data);
        return $data;
    }

    private function supplySignature($data)
    {
        $data['Signature'] = IpaySignatureGenerator::requestSignature($this->config['merchantKey'], $this->config['merchantCode'],
            $data['RefNo'], $data['Amount'], $data['Currency']);
        return $data;
    }

}