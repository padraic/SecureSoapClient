<?php

namespace alc;

require_once dirname(__FILE__) . '/../alc/SecureSoapClient.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecureSoapClientTest
 *
 * @author David
 */
class SecureSoapClientTest extends \PHPUnit_Framework_TestCase {

    /**
     * Testing that we can access a basic web service over http
     *
     * @covers \alc\SecureSoapClient::__call
     * @covers \alc\SecureSoapClient::__doRequest
     * @covers \alc\SecureSoapClient::callCurl
     * @covers \alc\SecureSoapClient::__construct
     */
    public function testHttpSoap()
    {
        $Client = new \alc\SecureSoapClient('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL', '/cache/');
        $Params = array(
            'FromCurrency' => 'GBP',
            'ToCurrency' => 'USD'
        );
        $Response = $Client->ConversionRate($Params);
        $Rate = $Response->ConversionRateResult;

        $this->assertTrue(is_numeric($Rate));
        $this->assertGreaterThan(0, $Rate);
    }

    /**
     * Testing that we can access a basic web service over http
     *
     * @covers \alc\SecureSoapClient::__call
     * @covers \alc\SecureSoapClient::__doRequest
     * @covers \alc\SecureSoapClient::callCurl
     * @covers \alc\SecureSoapClient::__construct
     */
    public function testHttpsSoap()
    {
        $Client = new \alc\SecureSoapClient('https://www.eway.com.au/gateway/rebill/test/manageRebill_test.asmx?WSDL', '/cache/');
        $Params = array(
            'customerTitle' => 'Mr',
            'customerFirstName' => 'Test',
            'customerLastName' => 'Testerson',
            'customerAddress' => '123 Test Road',
            'customerSuburb' => 'Testton',
            'customerState' => 'Test',
            'customerCompany' => 'Test Co.',
            'customerPostCode' => 'TEST',
            'customerCountry' => 'UK',
            'customerEmail' => 'test@test.com',
            'customerFax' => '',
            'customerPhone1' => '01234567890',
            'customerPhone2' => '',
            'customerRef' => 'test123',
            'customerJobDesc' => 'Tester',
            'customerComments' => 'I\'m testing your web service.',
            'customerURL' => ''
        );
        $Response = $Client->CreateRebillCustomer($Params);
        $RebillCustomer = $Response->CreateRebillCustomerResult;

        $this->assertInstanceOf('\StdClass', $RebillCustomer);
        $this->assertEquals($RebillCustomer->Result, 'Fail');
        $this->assertEquals($RebillCustomer->ErrorSeverity, 'Error');
        $this->assertEquals($RebillCustomer->ErrorDetails, "The 'eWayCustomerID' element has an invalid value according to its data type.");
    }

}

?>
