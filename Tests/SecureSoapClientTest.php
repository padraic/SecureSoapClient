<?php

namespace alc;

require_once dirname(__FILE__) . '/../alc/SecureSoapClient.php';
use alc\SecureSoapClient;

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
    public function testHttpSoap11()
    {
        $options = array('soap_version' => SOAP_1_1);
        $Client = new \alc\SecureSoapClient('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL', '/cache/', $options);
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
    public function testHttpSoap12()
    {
        $options = array('soap_version' => SOAP_1_2);
        $Client = new \alc\SecureSoapClient('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL', '/cache/', $options);
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
        $Client = new SecureSoapClient('https://www.eway.com.au/gateway/rebill/test/manageRebill_test.asmx?WSDL', '/cache/');
        $Params = array(
            'customerTitle' => 'Mr',
            'customerFirstName' => 'Joe',
            'customerLastName' => 'Jones',
            'customerAddress' => '123 Some Road',
            'customerSuburb' => 'Anytown',
            'customerState' => 'DE',
            'customerCompany' => 'Co Co.',
            'customerPostCode' => 'DE74PU',
            'customerCountry' => 'UK',
            'customerEmail' => 'me@me.com',
            'customerFax' => '',
            'customerPhone1' => '+4401234567890',
            'customerPhone2' => '',
            'customerRef' => 'ABC123',
            'customerJobDesc' => 'CEO',
            'customerComments' => '',
            'customerURL' => ''
        );
        $Response = $Client->CreateRebillCustomer($Params);
        $RebillCustomer = $Response->CreateRebillCustomerResult;

        $this->assertInstanceOf('\StdClass', $RebillCustomer);
        $this->assertEquals($RebillCustomer->Result, 'Fail');
        $this->assertEquals($RebillCustomer->ErrorSeverity, 'Error');
        $this->assertEquals($RebillCustomer->ErrorDetails, "The 'eWayCustomerID' element has an invalid value according to its data type.");
    }

    /**
     * Testing that we can access a basic web service over http
     *
     * @covers \alc\SecureSoapClient::__call
     * @covers \alc\SecureSoapClient::__doRequest
     * @covers \alc\SecureSoapClient::callCurl
     * @covers \alc\SecureSoapClient::__construct
     */
    public function testNullWsdl()
    {
        $options = array(
            'location' => 'http://www.webservicex.net/geoipservice.asmx',
            'uri' => 'http://www.webservicex.net/',
            'soap_version' => SOAP_1_1,
            'style' => SOAP_DOCUMENT,
            'use' => SOAP_LITERAL,
            'trace' => 1
        );

        $Client = new \alc\SecureSoapClient(null, '', $options);

        // Get an IP to query
        $IP = gethostbyname('google.com');

        // Nasty cludge because I couldn't be bothered figuring out how
        // to pass complex types to a web service in non-wsdl mode
        // for the sake of a single test case.
        $Param = '<GetGeoIP xmlns="http://www.webservicex.net/"><IPAddress>' . $IP . '</IPAddress></GetGeoIP>';

        $Response = $Client->GetGeoIP(new \SoapVar($Param, XSD_ANYXML));
        $GeoIP = $Response;

        $this->assertInstanceOf('\StdClass', $GeoIP);
        $this->assertEquals($IP, $GeoIP->IP);
        $this->assertTrue(is_numeric($GeoIP->ReturnCode));
        $this->assertTrue(is_string($GeoIP->CountryName));
        $this->assertTrue(is_string($GeoIP->CountryCode));
    }

}

?>
