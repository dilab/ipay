<?php
App::uses('IpayRequery', 'Ipay.Lib/Network');
App::uses('HttpSocket', 'Network/Http');


/**
 * Class IpayRequeryTest
 *
 * @property IpayRequery $IpayRequery
 */
class IpayRequeryIntegrationTest extends CakeTestCase
{

    public function setUp()
    {
        $httpClient = $this->getMock('HttpSocket');
        $this->IpayRequery = new IpayRequery($httpClient);
        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->IpayRequery);
    }

    public function testRequest()
    {
        $data = [
            'merchant_code' => 'apple',
            'ref_no' => 'ref_no',
            'amount' => '56.00',
        ];

        $this->IpayRequery = new IpayRequery(new HttpSocket());

        $result = $this->IpayRequery->request($data);

        $expected = 'Record not found';

        $this->assertEquals($expected, $result);
    }
}
