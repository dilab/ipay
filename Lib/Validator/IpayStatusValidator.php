<?php
App::uses('IpayValidator', 'Ipay.Lib/Validator');


class IpayStatusValidator implements IpayValidator
{

    public function isValid($data)
    {
        return $data['status'] ==  1;
    }
}