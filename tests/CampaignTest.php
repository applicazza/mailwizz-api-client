<?php

use Applicazza\MailwizzApiClient\Exceptions;
use Applicazza\MailwizzApiClient\MailwizzApiClient;
use Applicazza\MailwizzApiClient\MailwizzApiClientServiceProvider;
use Orchestra\Testbench\TestCase;

class CampaignTest extends TestCase
{
    public function testAll()
    {
        $mock = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], Stub::getStub('campaign', 'index')),
        ]);

        $client = new MailwizzApiClient('http://www.example.com', '123', '456', $mock);

        $campaigns = $client->campaigns->all();

        $this->assertNotEmpty($campaigns);
        $this->assertCount(2, $campaigns);
        $this->assertEquals('Test #1', $campaigns[0]->getName());
    }

    public function testConnectException()
    {
        $this->expectException(Exceptions\NetworkException::class);

        $mock = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Exception\ConnectException('', new GuzzleHttp\Psr7\Request('GET', '')),
        ]);

        $client = new MailwizzApiClient('', '', '', $mock);

        $client->campaigns->all();
    }

    public function testInitializationException()
    {
        $this->expectException(Exceptions\InitializationException::class);

        $client = new MailwizzApiClient();

        $client->campaigns->all();
    }

    public function testKeys()
    {
        $client = new MailwizzApiClient('http://www.example.com', '123', '456');
        $client->setKeys('123', '456');

        $this->assertEquals('123', $client->getPublicKey());
        $this->assertEquals('456', $client->getPrivateKey());
    }

    public function testLastRequest()
    {
        $mock = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], Stub::getStub('campaign', 'index')),
        ]);

        $client = new MailwizzApiClient('http://www.example.com', '123', '456', $mock);

        $client->campaigns->all();

        $this->assertEquals(Stub::getStub('campaign', 'index'), $client->getLastRequestHttpResponse());
        $this->assertEquals(200, $client->getLastRequestHttpStatusCode());
    }

    public function testProxy()
    {
        $proxy = 'socks5://127.0.0.1:8080';

        $client = new MailwizzApiClient('http://www.example.com', '123', '456');
        $client->setProxy($proxy);

        $this->assertEquals($proxy, $client->getProxy());
    }

    public function testRequestException()
    {
        $this->expectException(Exceptions\NetworkException::class);

        $mock = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Exception\RequestException('', new GuzzleHttp\Psr7\Request('GET', '')),
        ]);

        $client = new MailwizzApiClient('', '', '', $mock);

        $client->campaigns->all();
    }

    public function testTimeout()
    {
        $client = new MailwizzApiClient('http://www.example.com', '123', '456');
        $client->setKeys('123', '456');
        $client->setTimeout(60);

        $this->assertEquals(60, $client->getTimeout());
    }

    public function testTransferException()
    {
        $this->expectException(Exceptions\NetworkException::class);

        $mock = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Exception\TransferException(),
        ]);

        $client = new MailwizzApiClient('', '', '', $mock);

        $client->campaigns->all();
    }

    protected function getPackageProviders($app)
    {
        return [MailwizzApiClientServiceProvider::class];
    }
}