<?php
App::uses('IpayRequery', 'Ipay.Lib/Network');
App::uses('HttpSocketResponse', 'Network/Http');


/**
 * Class IpayRequeryTest
 *
 * @property IpayRequery $IpayRequery
 */
class IpayRequeryTest extends CakeTestCase
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

    public function queryProvider()
    {
        return [
            [
                [
                    'merchant_code' => 'apple',
                    'ref_no' => 'ref_no',
                    'amount' => '56.00',
                ],
                [
                    'MerchantCode' => 'apple',
                    'RefNo' => 'ref_no',
                    'Amount' => '56.00',
                ]
            ],
            [
                [
                    'merchant_code' => 'apple',
                    'ref_no' => 'ref_no',
                    'amount' => '56',
                ],
                [
                    'MerchantCode' => 'apple',
                    'RefNo' => 'ref_no',
                    'Amount' => '56.00',
                ]
            ],
        ];
    }

    /**
     * @dataProvider queryProvider
     */
    public function testQuery($data, $expected)
    {
        $this->assertEquals($expected, $this->IpayRequery->query($data));
    }

    public function testUri()
    {
        $expected = 'https://www.mobile88.com/epayment/enquiry.asp';

        $this->assertEquals($expected, $this->IpayRequery->uri());
    }

    public function testRequest()
    {
        $data = [
            'merchant_code' => 'apple',
            'ref_no' => 'ref_no',
            'amount' => '56.00',
        ];

        $httpClient = $this->getMock('HttpSocket', ['post']);

        $httpSocketResponse = new HttpSocketResponse();

        $httpClient->expects($this->once())->method('post')->will($this->returnValue($httpSocketResponse));

        $this->IpayRequery = new IpayRequery($httpClient);

        $this->IpayRequery->request($data);
    }

    public function testRequestReturnNetworkError()
    {
        $data = [
            'merchant_code' => 'apple',
            'ref_no' => 'ref_no',
            'amount' => '56.00',
        ];

        $httpClient = $this->getMock('HttpSocket', ['post']);

        $httpClient->expects($this->once())->method('post')->will($this->returnValue(false));

        $this->IpayRequery = new IpayRequery($httpClient);

        $expected = 'Network error';

        $this->assertEquals($expected,$this->IpayRequery->request($data));
    }
}
