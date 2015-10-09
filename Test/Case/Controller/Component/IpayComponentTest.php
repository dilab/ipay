<?php
App::uses('Controller', 'Controller');
App::uses('IpayComponent', 'Ipay.Controller/Component');
App::uses('IpayResponse', 'Ipay.Model');

// A fake controller to test against
class IpayControllerTest extends Controller
{
}

/**
 * Class IpayComponentTest
 *
 * @property IpayComponent $IpayComponent
 */
class IpayComponentTest extends CakeTestCase
{
    public $fixtures = array(
        'plugin.ipay.ipay_response'
    );

    public $Controller = null;

    public function setUp()
    {
        parent::setUp();

    }

    public function testProcessPaymentResponse()
    {
        $Collection = new ComponentCollection();
        $this->Ipay = new IpayComponent($Collection);

        $CakeRequest = $this->getMock('CakeRequest', ['request', 'is']);
        $CakeRequest->data = [
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
        $CakeRequest->expects($this->any())
            ->method('is')->will($this->returnValue(true));

        $CakeResponse = new CakeResponse();
        $this->Controller = new IpayControllerTest($CakeRequest, $CakeResponse);
        $this->Ipay->startup($this->Controller);

        return $this->assertInstanceOf('IpayResponse', $this->Ipay->processPaymentResponse());
    }


}