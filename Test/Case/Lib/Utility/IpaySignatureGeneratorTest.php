<?php
App::uses('IpaySignatureGenerator', 'Ipay.Lib/Utility');


/**
 * Class IpaySignatureGeneratorTest
 *
 * @property IpaySignatureGenerator $IpaySignatureGenerator
 */
class IpaySignatureGeneratorTest extends CakeTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
    }

    public function responseSignatureProvider()
    {
        return [
            [
                'apple','M00003', 2, '12345', '100', 'MYR' , 1,
                'a4THdPHQG9jT3DPZZ/mabkXUqow='
            ],
            [
                'apple','123', 1, '12345', '12,300.00', 'MYR' , 0,
                'hbayEZBzQ3/HlHIr4ED2mZif96M='
            ]
        ];
    }

    /**
     * @dataProvider responseSignatureProvider
     */
    public function testResponseSignature($merchantKey, $merchantCode, $paymentId, $refNo, $amount, $currency, $status, $expected)
    {
        $this->assertEquals($expected, IpaySignatureGenerator::responseSignature($merchantKey, $merchantCode, $paymentId, $refNo, $amount, $currency, $status));
    }


    public function requestSignatureProvider()
    {
        return [
            [
                'apple','M00003', 'A00000001', '100', 'MYR' , '84dNMbfgjLMS42IqSTPqQ99cUGA=',
                '84dNMbfgjLMS42IqSTPqQ99cUGA='
            ],
            [
                'orange', 'M00001','A00000002', '5630', 'MYR',
                'vsSIv1xBvrwqEWed5sIh/azYMBs='
            ],
            [
                'orange', 'M00001','A00000002', '110023','currency' => 'MYR',
                '9Joom12ViHELc8gkhghpgKRWuW4='
            ]
        ];
    }

    /**
     * @dataProvider requestSignatureProvider
     */
    public function testRequestSignature($merchantKey, $merchantCode, $refNo, $amount, $currency, $expected)
    {
        $this->assertEquals($expected, IpaySignatureGenerator::requestSignature($merchantKey, $merchantCode, $refNo, $amount, $currency));
    }

}
