<?php

use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testBasicRoute(): void
    {
        $client = new GuzzleHttp\Client();

        $response = $client->request('GET', 'localhost/patients');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMethodNotAllowed(): void
    {
        $client = new GuzzleHttp\Client();

        $response = $client->request('PUT', 'localhost/patients', ['exceptions' => false]);

        $this->assertEquals(405, $response->getStatusCode());
    }
}