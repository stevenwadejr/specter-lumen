<?php

class CustomersEndpointTest extends TestCase
{
    public function testSingleCustomerReturned()
    {
        $this->get('/customers/123', [
            'SpecterSeed' => 123
        ]);

        $json = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('__specter', $json);
    }

    public function testMultipleCustomersReturned()
    {
        $this->get('/customers');

        $json = json_decode($this->response->getContent(), true);

        $this->assertEquals(10, count($json));
    }
}
