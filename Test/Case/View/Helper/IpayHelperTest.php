<?php
App::uses('Controller', 'Controller');
App::uses('IpayHelper', 'Ipay.View/Helper');

/**
 * Class IpayHelperTest
 *
 * @property  IpayHelper $Ipay
 */
class IpayHelperTest extends CakeTestCase
{

    public function setUp()
    {
        parent::setUp();
        $Controller = new Controller();
        $View = new View($Controller);
        $this->Ipay = new IpayHelper($View);

        $this->userMandatoryFields = array(
            'RefNo' => '123',
            'Amount' => '1,278.99',
            'Currency' => 'test',
            'ProdDesc' => 'test',
            'UserName' => 'test',
            'UserEmail' => 'test',
            'UserContact' => 'test',
            'ResponseURL' => 'test',
        );

        $this->requestMandatoryFields = array(
            'MerchantCode' => 'test',
            'RefNo' => '123',
            'Amount' => '1,278.99',
            'Currency' => 'test',
            'ProdDesc' => 'test',
            'UserName' => 'test',
            'UserEmail' => 'test',
            'UserContact' => 'test',
            'Signature' => 'test',
            'ResponseURL' => 'test',
            'BackendURL' => 'test',
        );
    }

    public function testButtonContainsMandatoryFields()
    {
        $result = $this->Ipay->button($this->userMandatoryFields);
        foreach (array_keys($this->requestMandatoryFields) as $field) {
            $this->assertTextContains($field, $result);
        }
    }

    /**
     *
     * @expectedException NotFoundException
     */
    public function testUserMustAppliedRequiredFields()
    {
        unset($this->userMandatoryFields['Currency']);
        $this->Ipay->button($this->userMandatoryFields);
    }

    public function testButtonContainsOptionalFieldsIfSpecified()
    {
        $specifiedResponseUrl = 'http://www.specified_response_url';
        $this->userMandatoryFields['ResponseURL'] = $specifiedResponseUrl;
        $result = $this->Ipay->button($this->userMandatoryFields);
        $this->assertTextContains($specifiedResponseUrl, $result);
    }

}