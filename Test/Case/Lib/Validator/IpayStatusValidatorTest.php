<?php
App::uses('IpayStatusValidator', 'Ipay.Lib/Validator');


/**
 * Class IpayStatusValidatorTest
 *
 * @property IpayStatusValidator $IpayStatusValidator
 */
class IpayStatusValidatorTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.ipay.ipay_response'
    );

    public function setUp()
    {
        parent::setUp();
        $this->IpayStatusValidator = new IpayStatusValidator();
    }

    public function tearDown()
    {
        unset($this->IpayStatusValidator);
    }

    public function isStatusValidProvider()
    {
        return [
            [
                ['status'=>1],
                true
            ],
            [
                ['status'=>0],
                false
            ],
            [
                ['status'=>'1'],
                true
            ],
            [
                ['status'=>'0'],
                false
            ],
        ];
    }

    /**
     * @dataProvider isStatusValidProvider
     */
    public function testIsStatusValid($data, $expected)
    {
        $this->assertEquals($expected, $this->IpayStatusValidator->isValid($data));
    }

}
