<?php

namespace Omnipay\AuthorizeNet\Message;

use Omnipay\Tests\TestCase;

class CIMCreateCardResponseTest extends TestCase
{
    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testConstructEmpty()
    {
        new CIMCreateCardResponse($this->getMockRequest(), '');
    }

    public function testCreateCardSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CIMCreateCardSuccess.txt');
        $response = new CIMCreateCardResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('I00001', $response->getReasonCode());
        $this->assertEquals("1", $response->getResultCode());
        $this->assertEquals("Successful.", $response->getMessage());
        $this->assertEquals(
            '{"customerProfileId":"28972084","customerPaymentProfileId":"26317840","customerShippingAddressId":"27057149"}',
            $response->getCardReference()
        );
    }

    public function testCreateCardFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CIMCreateCardFailure.txt');
        $response = new CIMCreateCardResponse($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('E00041', $response->getReasonCode());
        $this->assertEquals("3", $response->getResultCode());
        $this->assertEquals("One or more fields in the profile must contain a value.", $response->getMessage());
        $this->assertNull($response->getCardReference());
    }
}