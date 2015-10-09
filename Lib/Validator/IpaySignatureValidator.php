<?php
App::uses('IpayValidator', 'Ipay.Lib/Validator');
App::uses('IpaySignatureGenerator', 'Ipay.Lib/Utility');

class IpaySignatureValidator implements IpayValidator
{

    public function isValid($data)
    {
        $expectedSignature = IpaySignatureGenerator::responseSignature($data['merchant_key'], $data['merchant_code'],
            $data['payment_id'], $data['ref_no'], $data['amount'], $data['currency'], $data['status']);

        return $expectedSignature == $data['signature'];
    }


}