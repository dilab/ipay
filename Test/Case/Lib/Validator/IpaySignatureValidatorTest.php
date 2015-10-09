<?php
App::uses('IpaySignatureValidator', 'Ipay.Lib/Validator');


/**
 * Class IpaySignatureValidatorTest
 *
 * @property IpaySignatureValidator $IpaySignatureValidator
 */
class IpaySignatureValidatorTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.ipay.ipay_response'
    );

    public function setUp()
    {
        parent::setUp();
        $this->IpaySignatureValidator = new IpaySignatureValidator();
    }

    public function tearDown()
    {
        unset($this->IpaySignatureValidator);
    }

    public function isSignatureValidProvider()
    {
        return [
          [
              ['merchant_key' => 'apple','merchant_code' => 'M00003', 'payment_id' => 2, 'ref_no' => '12345','amount' => '100',
               'currency' => 'MYR' , 'status'=>1,  'signature' => 'a4THdPHQG9jT3DPZZ/mabkXUqow='],
              true
          ],
          [
              ['merchant_key' => 'orange','merchant_code' => 'M00001', 'payment_id' => 1, 'ref_no' => 'A00000002','amount' => '5630',
              'currency' => 'MYR', 'status'=>1, 'signature' => '1jIA4LON3olDqnDuiD80zUHJm5Y='],
              true
          ],
          [
              ['merchant_key' => 'orange','merchant_code' => 'M00001', 'payment_id' => 2, 'ref_no' => 'A00000002','amount' => '110023',
               'currency' => 'MYR', 'status'=>1,  'signature' => 'invalidsignature='],
              false
          ]
        ];
    }

    /**
     * @dataProvider isSignatureValidProvider
     */
    public function testisSignatureValid($data, $expected)
    {
        $this->assertEquals($expected, $this->IpaySignatureValidator->isValid($data));
    }

}
