<?php
App::uses('Inflector', 'Utility');
App::uses('IpayStatusValidator', 'Ipay.Lib/Validator');
App::uses('IpayRequery', 'Ipay.Lib/Network');
App::uses('IpayAppModel', 'Ipay.Model');
App::uses('IpayResponse', 'Ipay.Model');


/**
 * Class IpayResponseTest
 *
 * @property IpayResponse $IpayResponse
 */
class IpayResponseTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.ipay.ipay_response'
    );

    public function setUp()
    {
        parent::setUp();

        $this->IpayResponse = ClassRegistry::init('Ipay.IpayResponse');

        $this->response = [
            'MerchantCode' => '123',
            'PaymentId' => 1,
            'RefNo' => 'ref',
            'Amount' => '123',
            'Currency' => 'MYR',
            'Remark' => '100',
            'TransId' => '',
            'AuthCode' => '',
            'Status' => 1,
            'ErrDesc' => '',
            'Signature' => 'sig',
        ];
    }

    public function tearDown()
    {
        unset($this->IpayResponse);
        parent::tearDown();
    }


    public function testProcessCanBecomeSuccess()
    {

        $requeryStatus = IpayResponse::$successRequeryStatus;

        $validator = $this->getMock('IpayStatusValidator', ['isValid']);
        $validator->expects($this->once())->method('isValid')->will($this->returnValue(true));

        $requeryClient = $this->getMock('IpayRequery', array('request'), array($this->getMock('HttpSocket')));
        $requeryClient->expects($this->once())->method('request')->will($this->returnValue($requeryStatus));

        $this->IpayResponse->addResponseValidator($validator);
        $this->IpayResponse->useRequeryClient($requeryClient);

        $this->IpayResponse->process($this->response);

        $result = $this->IpayResponse->find('first', [
            'conditions' => ['IpayResponse.remark' => $this->response['Remark']]
        ]);

        $this->assertTrue($result['IpayResponse']['is_success']);
        $this->assertEquals($requeryStatus, $result['IpayResponse']['requery_response']);
    }

    public function testProcessCanFailAtResponseValidation()
    {

        $requeryStatus = 'Invalid parameters';

        $validator = $this->getMock('IpayStatusValidator', ['isValid']);
        $validator->expects($this->once())->method('isValid')->will($this->returnValue(false));

        $requeryClient = $this->getMock('IpayRequery', array(), array($this->getMock('HttpSocket')));
        $requeryClient->expects($this->exactly(0))->method('request')->will($this->returnValue($requeryStatus));

        $this->IpayResponse->addResponseValidator($validator);
        $this->IpayResponse->useRequeryClient($requeryClient);

        $this->IpayResponse->process($this->response);

        $result = $this->IpayResponse->find('first', [
            'conditions' => ['IpayResponse.remark' => $this->response['Remark']]
        ]);

        $this->assertFalse($result['IpayResponse']['is_success']);
        $this->assertEmpty($result['IpayResponse']['requery_response']);
    }

    public function testProcessCanFailAtRequery()
    {
        $requeryStatus = 'Invalid parameters';

        $validator = $this->getMock('IpayStatusValidator', ['isValid']);
        $validator->expects($this->once())->method('isValid')->will($this->returnValue(true));

        $requeryClient = $this->getMock('IpayRequery', array(), array($this->getMock('HttpSocket')));
        $requeryClient->expects($this->exactly(1))->method('request')->will($this->returnValue($requeryStatus));

        $this->IpayResponse->addResponseValidator($validator);
        $this->IpayResponse->useRequeryClient($requeryClient);

        $this->IpayResponse->process($this->response);

        $result = $this->IpayResponse->find('first', [
            'conditions' => ['IpayResponse.remark' => $this->response['Remark']]
        ]);

        $this->assertFalse($result['IpayResponse']['is_success']);
        $this->assertEquals($requeryStatus, $result['IpayResponse']['requery_response']);
    }

}
