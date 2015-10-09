<?php
App::uses('IpayAppModel', 'Ipay.Model');
App::uses('IpayValidator', 'Ipay.Lib/Validator');
App::uses('IpayRequery', 'Ipay.Lib/Network');

class IpayResponse extends IpayAppModel
{
    static $successRequeryStatus = '00';

    private $requeryResponse = '';

    private $responseValidators = [];

    private $requeryClient = null;

    private $dbPrimaryId = 0;

    public function isSuccess()
    {
        return $this->isSuccessRequery();
    }

    public function addResponseValidator(IpayValidator $validator)
    {
        $this->responseValidators[] = $validator;
    }

    public function useRequeryClient(IpayRequery $requeryClient)
    {
        $this->requeryClient = $requeryClient;
    }

    public function process($response)
    {
        $this->responseToDb($response);

        return $this->processResponse() && $this->processRequery();
    }

    private function processResponse()
    {
        if ($this->validResponse()) {
            return $this->processValidResponse();
        }


        return false;
    }

    private function processRequery()
    {
        $this->requery($this->dbPrimaryId);

        if ($this->isSuccessRequery()) {
            return $this->processSuccessRequery();
        }

        return $this->processFailRequery();
    }

    private function responseToDb($response)
    {
        $keys = array_map(function ($item) {
            return Inflector::underscore($item);
        }, array_keys($response));
        $value = array_values($response);
        $data = array_combine($keys, $value);

        $mandatoryFields = array('merchant_code', 'payment_id', 'ref_no', 'amount', 'currency', 'status', 'signature');
        foreach ($mandatoryFields as $field) {
            $this->validator()->add($field, 'notEmpty', array(
                'rule' => 'notEmpty',
                'required' => 'create'
            ));
        }

        $this->create();
        if (false === $this->save($data)) {
            throw new BadRequestException('Unable to save response data');
        }

        return $this->dbPrimaryId = $this->id;
    }

    private function validResponse()
    {
        $this->recursive = -1;
        $response = $this->read(null, $this->dbPrimaryId);

        $data = $response['IpayResponse'];
        $data['merchant_key'] = Configure::read('Ipay.merchantKey');

        foreach ($this->responseValidators as $validator) {
            if (!$validator->isValid($data)) {
                CakeLog::write('ipay', __('Validator %s failed for purchase with id %s', get_class($validator), $this->dbPrimaryId));
                return false;
            }
        }

        return true;
    }

    private function processValidResponse()
    {
        $event = new CakeEvent('Model.IpayResponse.afterValidResponse', $this, array(
            'id' => $this->dbPrimaryId
        ));
        $this->getEventManager()->dispatch($event);

        return true;
    }

    private function requery()
    {
        $this->recursive = -1;
        $response = $this->read(null, $this->dbPrimaryId);
        $this->requeryResponse = $this->requeryClient->request($response['IpayResponse']);
    }

    private function isSuccessRequery()
    {
        return self::$successRequeryStatus == $this->requeryResponse;
    }

    private function processSuccessRequery()
    {
        $this->create();
        $this->id = $this->dbPrimaryId;
        $this->saveField('is_success', true);
        $this->saveField('requery_response', $this->requeryResponse);

        $event = new CakeEvent('Model.IpayResponse.afterSuccessResponse', $this, array(
            'id' => $this->dbPrimaryId
        ));
        $this->getEventManager()->dispatch($event);

        return true;
    }

    private function processFailRequery()
    {
        $this->create();
        $this->id = $this->dbPrimaryId;
        $this->saveField('is_success', false);
        $this->saveField('requery_response', $this->requeryResponse);

        CakeLog::write('ipay', __('Failed requery for ipay88 payment with id %s', $this->dbPrimaryId));

        return false;
    }

}